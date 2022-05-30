<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\WorkTime;
use App\Models\WorkType;
use App\Models\FixedTime;
use App\Models\PaidLeave;
use Carbon\Carbon;
use Yasumi\Yasumi;

class InputFormController extends Controller
{
    public function show(Request $request){
        $user = Auth::user();

        // 当日の日付表示と月表示を取得する
        Carbon::setLocale('ja');
        $today = Carbon::createFromDate();

        // 月選択フォームから表示月を指定された場合
        if (isset($request->month)) {
            $month = new Carbon($request->month); //選択月のデータを取得
            $year = $month->format('Y');
            $holidays = Yasumi::create('Japan', $year, 'ja_JP'); //選択月の年間の祝日を取得
            $current_month = date("Y-m", strtotime($request->month)); //選択月のデータを変数に格納

        // 勤怠入力フォームに直接アクセスされた場合
        } else {
            $month = Carbon::createFromDate();
            $now = now();
            $holidays = Yasumi::create('Japan', $now->year, 'ja_JP');
            $current_month = date("Y-m");
        }

        // 当日の打刻メモが存在すれば取得する
        $date = date("Y-m-d");
        $description = WorkTime::select('description')->where('user_id', $user->id)->where('date', $date)->first();
        
        $dt = $month->copy()->startOfMonth(); //今月の最初の日
        $dt->timezone = 'Asia/Tokyo'; //日本時刻で表示
        $daysInMonth = $dt->daysInMonth; //今月は何日まであるか

        $work_times = WorkTime::where('user_id', $user->id)->where('date', 'like', $current_month . '%')->get();
        $fixed_time = FixedTime::first();

        // 有効な有給休暇の数を取得する
        $paid_leaves = PaidLeave::where('user_id', $user->id)->whereNull('deleted_at')->get();
        $paid_leave_sum = 0;
        foreach ($paid_leaves as $paid_leave) {
            $paid_leave_sum += $paid_leave->left_days;
        }

        // 既定の労働時間を取得する
        $fixed_rest_time = $this->getRestTime($fixed_time->rest_time);
        $fixed_work_time = strtotime($fixed_time->left_time) - strtotime($fixed_time->start_time);
        $fixed_work_time = strtotime($fixed_rest_time, $fixed_work_time);
        $fixed_work_time = gmdate('H:i', $fixed_work_time);

        // 有給の取得予定数を表示する
        $approved_paid_leaves = Application::where('user_id', $user->id)
            ->where('status', 1)
            ->where('application_type_id', 1)
            ->where('date', '>', $today->isoFormat('YYYY-MM-DD'))
            ->count();

        return view('input.input', compact(
            'today',
            'month',
            'dt',
            'daysInMonth',
            'work_times',
            'fixed_time',
            'paid_leave_sum',
            'user',
            'description',
            'holidays',
            'fixed_work_time',
            'approved_paid_leaves',
        ));
    }

    public function add(Request $request){
        $fixed_time = FixedTime::first(); //定時のデータを取得
        $date = date("Y-m-d"); //打刻した時の日付を取得
        $time = date("H:i"); //打刻した時の時間を取得

        // 打刻メモに対するバリデーション
        $rules = ['description' => 'max:60',];
        $messages = ['description.max' => '60文字以下で入力してください',];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 出勤ボタンを打刻した時の処理
        if (isset($request->start_time)){

            // ログインユーザーの当日のレコードが存在する場合
            if (DB::table('work_times')->where('user_id', $request->user_id)->where('date', $date)->exists()) {
                return redirect('/')->with('message', '既に勤怠の入力が完了しています');

            // レコードが存在しない場合はレコードを作成する
            } else {
                $work_time = new WorkTime;
                $work_time->user_id = $request->user_id;

                // 打刻開始時刻が始業時刻を超えていた場合、「遅刻」を打刻する
                if (strtotime($time) >= strtotime($fixed_time->start_time)) {
                    $work_time->work_type_id = 3;
                // 超えていない場合は「出勤」を打刻する
                } else {
                    $work_time->work_type_id = 1;
                }
                // 必要なデータを格納し、保存
                $work_time->date = $date;
                $work_time->start_time = $time;
                $work_time->over_time = '00:00:00';
                $work_time->description = $request->description;
                $work_time->save();
            }
        }

        // 退勤ボタンを打刻した時の処理
        if (isset($request->left_time)) {
            $work_time = WorkTime::where('user_id', $request->user_id)->where('date', $date)->first();
            
            // ログインユーザーの当日のレコードが存在しないかチェック
            if (WorkTime::where('user_id', $request->user_id)->where('date', $date)->doesntExist() || empty($work_time->start_time)) {
                return redirect('/')->with('message', '出勤の打刻が完了していません');
            } elseif ($work_time->left_time !== NULL) {
                return redirect('/')->with('message', '既に退勤の打刻が完了しています');
            }

            // 勤務時間から差し引く既定の休憩時間を取得
            $calculate_fixed_rest = $this->getRestTime($fixed_time->rest_time);

            // 実労働時間(勤務時間 - 既定の休憩時間)を分で取得
            // 規定時刻より早く出社した場合
            if ($work_time->start_time < $fixed_time->start_time) {
                $worked_time = $this->getWorkedTime($time, $fixed_time->start_time, $calculate_fixed_rest);
            // 規定時刻より後に出社した場合
            } else {
                $worked_time = $this->getWorkedTime($time, $work_time->start_time, $calculate_fixed_rest);
            }
            
            //休憩時間の処理
            // 実労働時間が定時から６時間に満たない場合は、休憩時間を00:00:00にする
            if ($worked_time < 360) {
                WorkTime::where('user_id', $request->user_id)->where('date', date("Y-m-d"))->update([
                    'left_time' => $time,
                    'description' => $request->description,
                    'rest_time' => '00:00:00',
                ]);
            // 実労働時間が８時間を超える場合で、かつ既定の休憩時間が１時間未満の場合、休憩時間を「01:00:00」にする
            } elseif ($worked_time < 480 && $fixed_time->rest_time < '01:00:00') {
                WorkTime::where('user_id', $request->user_id)->where('date', $date)->update([
                    'left_time' => $time,
                    'description' => $request->description,
                    'rest_time' => '01:00:00',
                ]);
            // その他の場合は、既定の休憩時間を入れる
            } else {
                WorkTime::where('user_id', $request->user_id)->where('date', $date)->update([
                    'left_time' => $time,
                    'description' => $request->description,
                    'rest_time' => $fixed_time->rest_time,
                ]);
            }

            // 時間外労働の処理
            $fixed_left_over = strtotime($fixed_time->getRoundTime(), strtotime($fixed_time->left_time));
            $left_time = strtotime($time);
            if ($left_time >= $fixed_left_over) {
                $over_time = $left_time - $fixed_left_over;
                $over_time = gmdate("H:i", $over_time);
                WorkTime::where('user_id', $request->user_id)->where('date', $date)->update([
                    'over_time' => $over_time,
                ]);
            }

            // 勤務区分の処理
            // 定時よりも退勤打刻時間が早い場合、遅刻の時は「遅刻/早退」、そうでない場合は「早退」に更新する 
            if ((strtotime($time) < strtotime($fixed_time->left_time))) {
                if ($work_time->work_type_id == 3) {
                    WorkTime::where('user_id', $request->user_id)->where('date', $date)->update([
                    'work_type_id' => 5,
                    ]);
                } else {
                    WorkTime::where('user_id', $request->user_id)->where('date', $date)->update([
                    'work_type_id' => 4,
                    ]);
                }
            }
        }

        // 打刻メモ編集処理
        if (isset($request->description_submit)) {

            if (WorkTime::where('user_id', $request->user_id)->where('date', $date)->doesntExist()) {
                return redirect('/')->with('message', '出勤の打刻が完了していません');
            } else {
                WorkTime::where('user_id', $request->user_id)->where('date', $date)->update([
                'description' => $request->description,
                ]);
            }
        }
        return redirect('/')->with('sent_form', '打刻を完了しました');
    }

    public function getRestTime($rest) {
        $from = strtotime('00:00:00');
        $end = strtotime($rest);
        $minutes = ($end - $from) / 60;
        $calculate_rest = "-" . $minutes . "min";
        return $calculate_rest;
    }

    public function getWorkedTime($left, $start, $calculate_rest) {
        $worked_time = (strtotime($left) - strtotime($start));
        $worked_time = strtotime($calculate_rest, $worked_time) / 60;
        return $worked_time;
    }

}
