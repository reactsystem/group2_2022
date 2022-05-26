<?php

namespace App\Http\Controllers;

use App\Models\AddPaidLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkTime;
use App\Models\WorkType;
use App\Models\FixedTime;
use App\Models\PaidLeave;
use App\Models\User;
use App\Models\Application;
use Carbon\Carbon;
use Yasumi\Yasumi;
use Illuminate\Support\Facades\Validator;

class PersonalMgmtController extends Controller
{
    public function index(Request $request) {

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
        $description = DB::table('work_times')->select('description')->where('date', $date)->first();
        
        $dt = $month->copy()->startOfMonth(); //今月の最初の日
        $dt->timezone = 'Asia/Tokyo'; //日本時刻で表示
        $daysInMonth = $dt->daysInMonth; //今月は何日まであるか

        $user = User::find($request->user_id);
        $work_types = WorkType::whereNull('deleted_at')->get();
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

        // 退勤時刻を丸める範囲を取得する
        if (strtotime('01:00:00') > strtotime($fixed_time->rest_time)) {
            $round_time = strtotime('01:00:00') - strtotime($fixed_time->rest_time);
            $round_time = gmdate('H:i:s', $round_time);
            $round_time = $this->getRoundTime($round_time);
        } else {
            $round_time = '+0 min';
        }
        

        return view('manager.personal_mgmt', compact(
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
            'work_types',
            'fixed_work_time',
            'round_time',
        ));
    }

    public function update(Request $request) {
        $items = $request->all();
        $count = count($items['date']);
        $today = Carbon::createFromDate();
        $fixed_time = FixedTime::first();

        for ($i = 0; $i < $count; $i++)
        {
            if ($items['work_type'][$i] !== NULL) {

                // 勤務区分が「delete」の場合はレコードを削除するため、バリデーションをかけない
                // 日付が本日のデータは入力途中の可能性があるため、バリデーションをかけない
                if ($items['work_type'][$i] != 'delete' && $items['date'][$i] != $today->isoFormat('YYYY-MM-DD')) {
                    // ここからバリデーション
                    // 勤務区分が「欠勤」「有給休暇」「特別休暇」の場合、時刻を入力させない
                    if ($items['work_type'][$i] == 2 || $items['work_type'][$i] == 6 || $items['work_type'][$i] == 7) {
                        $check = ['start_time' => $items['start_time'][$i], 'left_time' => $items['left_time'][$i],];
                        $rules = [
                            'start_time' => 'max:0',
                            'left_time' => 'max:0',
                        ];
                        $messages = [
                            'start_time.max' => $items['date'][$i] .' :「欠勤」「有給休暇」「特別休暇」の場合は開始時刻を入力できません',
                            'left_time.max' => $items['date'][$i] .' :「欠勤」「有給休暇」「特別休暇」の場合は終了時刻を入力できません',
                        ];
                        $validator = Validator::make($check, $rules, $messages);
                    } else { // 勤務区分がその他の場合、必ず時刻を入力させる
                        $check = ['start_time' => $items['start_time'][$i], 'left_time' => $items['left_time'][$i],];
                        $rules = [
                            'start_time' => 'required|date_format:H:i',
                            'left_time' => 'required|date_format:H:i|after:start_time',
                        ];
                        $messages = [
                            'start_time.required' => $items['date'][$i] .' :開始時刻を入力してください',
                            'left_time.required' => $items['date'][$i] .' :終了時刻を入力してください',
                            'start_time.date_format' => $items['date'][$i] .' :開始時刻は「00:00」～「23:59」の範囲で入力してください',
                            'left_time.date_format' => $items['date'][$i] .' :終了時刻は「00:00」～「23:59」の範囲で入力してください',
                            'left_time.after' => $items['date'][$i] .' :終了時刻は開始時刻より後の時刻を入力してください',
                        ];
                        $validator = Validator::make($check, $rules, $messages);
                    }

                    if ($validator->fails()) {
                        return back()
                            ->withErrors($validator)
                            ->withInput();
                    }
                }

                // 「有給休暇」「特別休暇」をその他の勤務区分へ変更する場合、当該申請のステータスを「３(取り消し)」へ更新する
                if (WorkTime::where('user_id', $items['user_id'])->where('date', $items['date'][$i])->exists()) {
                    $work_time = WorkTime::where('user_id', $items['user_id'])->where('date', $items['date'][$i])->first();
                    if ($work_time->work_type_id == 6 || $work_time->work_type_id == 7) {
                        if ($items['work_type'][$i] !== 6 && $items['work_type'][$i] !== 7) {
                            $application = Application::where('user_id', $items['user_id'])
                                                        ->where('date', $items['date'][$i])
                                                        ->where('application_type_id', 1)
                                                        ->orWhere('application_type_id', 2)
                                                        ->first();
                            $application->status = 3;
                            $application->save();
                        }
                    }
                }

                // 勤務区分が「delete」の場合はレコードを削除
                if ($items['work_type'][$i] == 'delete')
                {
                    WorkTime::where('user_id', $items['user_id'])
                    ->where('date', $items['date'][$i])
                    ->delete();
                // その他の場合、レコードの有無によって更新処理
                } elseif (WorkTime::where('user_id', $items['user_id'])->where('date', $items['date'][$i])->exists())
                {
                    // 勤務時間から差し引く既定の休憩時間を取得
                    $calculate_rest = $this->getRestTime($fixed_time->rest_time);
                    
                    // 実労働時間(勤務時間 - 休憩時間)を分で取得
                    // 規定時刻より早く出社した場合
                    if ($work_time->start_time < $fixed_time->start_time) {
                        $worked_time = $this->getWorkedTime($items['left_time'][$i], $fixed_time->start_time, $calculate_rest);
                    // 規定時刻より後に出社した場合
                    } else {
                        $worked_time = $this->getWorkedTime($items['left_time'][$i], $work_time->start_time, $calculate_rest);
                    }

                    // 休憩時間の処理
                    // 実労働時間が６時間に満たない場合は、休憩時間に「00:00:00」を追加
                    if ($worked_time < 360) {
                        $rest_time = '00:00:00';
                    // 実労働時間が８時間を超える場合で、かつ既定の休憩時間が１時間未満の場合、休憩時間を「01:00:00」にする
                    } elseif ($worked_time >= 480 && $fixed_time->rest_time < '01:00:00') {
                        $rest_time = '01:00:00';
                    } else {
                        $rest_time = $fixed_time->rest_time;
                    }

                    // 時間外労働の処理
                    $fixed_left_over = strtotime("+15 min", strtotime($fixed_time->left_time));
                    $left_time = strtotime($items['left_time'][$i]);
                    if ($left_time >= $fixed_left_over) {
                        $over_time = $left_time - $fixed_left_over;
                        $over_time = gmdate("H:i", $over_time);
                    } else {
                        $over_time = '00:00:00';
                    }

                    WorkTime::where('user_id', $items['user_id'])
                        ->where('date', $items['date'][$i])
                        ->update([
                            'work_type_id' => $items['work_type'][$i],
                            'start_time' => $items['start_time'][$i],
                            'left_time' => $items['left_time'][$i],
                            'rest_time' => $rest_time,
                            'over_time' => $over_time,
                    ]);
                } else {
                    $work_time = new WorkTime;
                    $work_time->date = $items['date'][$i];
                    $work_time->user_id = $items['user_id'][$i];
                    $work_time->work_type_id = $items['work_type'][$i];
                    $work_time->start_time = $items['start_time'][$i];
                    $work_time->left_time = $items['left_time'][$i];

                    // 勤務時間から差し引く既定の休憩時間を取得
                    $calculate_rest = $this->getRestTime($fixed_time->rest_time);
                    
                    // 実労働時間(勤務時間 - 休憩時間)を分で取得
                    // 規定時刻より早く出社した場合
                    if ($work_time->start_time < $fixed_time->start_time) {
                        $worked_time = $this->getWorkedTime($work_time->left_time, $fixed_time->start_time, $calculate_rest);
                    // 規定時刻より後に出社した場合
                    } else {
                        $worked_time = (strtotime($work_time->left_time) - strtotime($work_time->start_time));
                        $worked_time = $this->getWorkedTime($work_time->left_time, $work_time->start_time, $calculate_rest);
                    }

                    // 休憩時間の処理
                    // 実労働時間が６時間に満たない場合は、休憩時間に「00:00:00」を追加
                    if ($worked_time < 360) {
                        $work_time->rest_time = '00:00:00';
                    // 実労働時間が８時間を超える場合で、かつ既定の休憩時間が１時間未満の場合、休憩時間を「01:00:00」にする
                    } elseif ($worked_time >= 480 && $fixed_time->rest_time < '01:00:00') {
                        $work_time->rest_time = '01:00:00';
                    } else {
                        $work_time->rest_time = $fixed_time->rest_time;
                    }

                    // 時間外労働の処理
                    $fixed_left_over = strtotime("+15 min", strtotime($fixed_time->left_time));
                    $left_time = strtotime($items['left_time'][$i]);
                    if ($left_time >= $fixed_left_over) {
                        $over_time = $left_time - $fixed_left_over;
                        $over_time = gmdate("H:i", $over_time);
                        $work_time->over_time = $over_time;
                    } else {
                        $work_time->over_time = '00:00:00';
                    }

                    $work_time->save();
                }
            }
        }
        return back()
            ->with('message', '勤務表を更新しました');
    }

    public function getRestTime($rest) {
        $from = strtotime('00:00:00');
        $end = strtotime($rest);
        $minutes = ($end - $from) / 60;
        $calculate_rest = "-" . $minutes . "min";
        return $calculate_rest;
    }

    public function getRoundTime($round) {
        $from = strtotime('00:00:00');
        $end = strtotime($round);
        $minutes = ($end - $from) / 60;
        $round_time = "+" . $minutes . "min";
        return $round_time;
    }

    public function getWorkedTime($left, $start, $calculate_rest) {
        $worked_time = (strtotime($left) - strtotime($start));
        $worked_time = strtotime($calculate_rest, $worked_time) / 60;
        return $worked_time;
    }

}
