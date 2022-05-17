<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkTime;
use App\Models\WorkType;
use App\Models\FixedTime;
use App\Models\PaidLeave;
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

        $user = Auth::user();
        $work_times = WorkTime::where('user_id', $user->id)->where('date', 'like', $current_month . '%')->get();
        $fixed_time = FixedTime::first();
        $paid_leaves = PaidLeave::where('user_id', $user->id)->first();

        return view('manager.personal_mgmt', compact(
            'today',
            'month',
            'dt',
            'daysInMonth',
            'work_times',
            'fixed_time',
            'paid_leaves',
            'user',
            'description',
            'holidays',
        ));
    }

    public function update(Request $request) {
        $items = $request->all();
        $count = count($items['date']);

        for ($i = 0; $i < $count; $i++)
        {
            if ($items['work_type'][$i] !== NULL) {

                // バリデーション
                // 勤務区分が「欠勤」「有給休暇」「特別休暇」の場合
                if ($items['work_type'][$i] == 2 || $items['work_type'][$i] == 5 || $items['work_type'][$i] == 6) {
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

                    if ($validator->fails()) {
                        return back()
                            ->withErrors($validator)
                            ->withInput();
                    }
                }

                // 勤務区分が「出勤」「遅刻」「早退」「遅刻/早退」の場合
                if ($items['work_type'][$i] == 1 || $items['work_type'][$i] == 3 || $items['work_type'][$i] == 4 || $items['work_type'][$i] == 7) {
                    $check = ['start_time' => $items['start_time'][$i], 'left_time' => $items['left_time'][$i],];
                    $rules = [
                        'start_time' => 'required|date_format:H:i',
                        'left_time' => 'required|date_format:H:i',
                    ];
                    $messages = [
                        'start_time.required' => $items['date'][$i] .' :開始時刻を入力してください',
                        'left_time.required' => $items['date'][$i] .' :終了時刻を入力してください',
                        'start_time.date_format' => $items['date'][$i] .' :開始時刻は「00:00」～「23:59」の範囲で入力してください',
                        'left_time.date_format' => $items['date'][$i] .' :終了時刻は「00:00」～「23:59」の範囲で入力してください',
                    ];
                    $validator = Validator::make($check, $rules, $messages);

                    if ($validator->fails()) {
                        return back()
                            ->withErrors($validator)
                            ->withInput();
                    }    
                }

                if (WorkTime::where('user_id', $items['user_id'])->where('date', $items['date'][$i])->exists())
                {
                    WorkTime::where('user_id', $items['user_id'])
                        ->where('date', $items['date'][$i])
                        ->update([
                            'work_type_id' => $items['work_type'][$i],
                            'start_time' => $items['start_time'][$i],
                            'left_time' => $items['left_time'][$i],
                    ]);
                } else {
                    $work_time = new WorkTime;
                    $work_time->date = $items['date'][$i];
                    $work_time->user_id = $items['user_id'][$i];
                    $work_time->work_type_id = $items['work_type'][$i];
                    $work_time->start_time = $items['start_time'][$i];
                    $work_time->left_time = $items['left_time'][$i];
                    $work_time->save();
                }
            }
        }
        return back()
            ->with('message', '勤務表を更新しました');
    }
}
