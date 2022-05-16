<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\WorkType;
use App\Models\FixedTime;
use App\Models\PaidLeave;
use Carbon\Carbon;
use Yasumi\Yasumi;

class InputFormController extends Controller
{
    public function show(Request $request){

        // 当日の日付表示と月表示を取得する
        Carbon::setLocale('ja');
        $today = Carbon::createFromDate();
        $month = Carbon::createFromDate();

        // 年間の祝日を取得する
        $now = now();
        $holidays = Yasumi::create('Japan', $now->year, 'ja_JP');

        // 当日の打刻メモが存在すれば取得する
        $date = date("Y-m-d");
        $description = DB::table('work_times')->select('description')->where('date', $date)->first();
        
        // 今月の最初の日付を取得する
        $dt = Carbon::createFromDate();
        $dt->startOfMonth(); //今月の最初の日
        $dt->timezone = 'Asia/Tokyo'; //日本時刻で表示
        $daysInMonth = $dt->daysInMonth; //今月は何日まであるか

        $user = Auth::user();
        $current_month = date("Y-m");
        $work_times = WorkTime::where('user_id', $user->id)->where('date', 'like', $current_month . '%')->get();
        $fixed_time = FixedTime::first();
        $paid_leaves = PaidLeave::where('user_id', $user->id)->first();

        $count_paid_leaves = $work_times->where('work_type_id', 5)->count();

        return view('input.input', [
            'today' => $today,
            'month' => $month,
            'dt' => $dt,
            'daysInMonth' => $daysInMonth,
            'work_times' => $work_times,
            'fixed_time' => $fixed_time,
            'paid_leaves' => $paid_leaves,
            'user' => $user,
            'description' => $description,
            'count_paid_leaves' => $count_paid_leaves,
            'holidays' => $holidays,
        ]);
    }

    public function add(Request $request){
        $fixed_time = FixedTime::find(1);
        $date = date("Y-m-d");
        $time = date("H:i:s");

        // 出勤処理
        if (isset($request->start_time)){

            // ログインユーザーの当日のレコードが存在しないかチェック
            if (DB::table('work_times')->where('user_id', $request->user_id)->where('date', $date)->exists()) {
                return redirect('/')->with('message', '既に出勤の打刻が完了しています');
            } else {
                $work_time = new WorkTime;
                $work_time->user_id = $request->user_id;

                // 打刻開始時刻が始業時刻を超えていた場合、「遅刻」を打刻する
                if (strtotime($time) >= strtotime($fixed_time->start_time)) {
                    $work_time->work_type_id = 3;
                } else {
                    $work_time->work_type_id = 1;
                }

                $work_time->date = $date;
                $work_time->start_time = $time;
                $work_time->over_time = '00:00:00';
                $work_time->description = $request->description;
                $work_time->save();
            }
        }

        // 退勤処理
        if (isset($request->left_time)){
            $work_time = DB::table('work_times')->where('user_id', $request->user_id)->where('date', $date)->first();
            
            // ログインユーザーの当日のレコードが存在しないかチェック
            if (DB::table('work_times')->where('user_id', $request->user_id)->where('date', $date)->doesntExist()) {
                return redirect('/')->with('message', '出勤の打刻が完了していません');
            } elseif ($work_time->left_time !== NULL) {
                return redirect('/')->with('message', '既に退勤の打刻が完了しています');
            } else {
                WorkTime::where('user_id', $request->user_id)->where('date', date("Y-m-d"))->update([
                    'left_time' => $time,
                    'rest_time' => '00:45:00',
                    'description' => $request->description,
                ]);

                // 定時よりも退勤打刻時間が早い場合、既に遅刻の時は「遅刻/早退」、そうでない場合は「早退」に更新する 
                if ((strtotime($time) < strtotime($fixed_time->left_time))) {
                    if ($work_time->work_type_id == 3) {
                        WorkTime::where('user_id', $request->user_id)->where('date', date("Y-m-d"))->update([
                        'work_type_id' => 7,
                        ]);
                    } else {
                        WorkTime::where('user_id', $request->user_id)->where('date', date("Y-m-d"))->update([
                        'work_type_id' => 4,
                        ]);
                    }
                }
            }
        }

        // 打刻メモ編集処理
        if (isset($request->description_submit)) {

            if (DB::table('work_times')->where('date', $date)->doesntExist()) {
                return redirect('/')->with('message', '出勤の打刻が完了していません');
            } else {
                WorkTime::where('user_id', $request->user_id)->where('date', date("Y-m-d"))->update([
                'description' => $request->description,
                ]);
            }
        }
        return redirect('/');
    }

    public function selectMonth(Request $request){

        // 当日の日付表示と月表示を取得する
        Carbon::setLocale('ja');
        $today = Carbon::createFromDate();
        $month = new Carbon($request->month);

        // 年間の祝日を取得する
        $year = $month->format('Y');
        $holidays = Yasumi::create('Japan', $year, 'ja_JP');

        // 当日の打刻メモが存在すれば取得する
        $date = date("Y-m-d");
        $description = DB::table('work_times')->select('description')->where('date', $date)->first();
        
        // 今月の最初の日付を取得する
        $dt = new Carbon($request->month);
        $dt->startOfMonth(); //今月の最初の日
        $dt->timezone = 'Asia/Tokyo'; //日本時刻で表示
        $daysInMonth = $dt->daysInMonth; //今月は何日まであるか

        $user = Auth::user();
        $current_month = date("Y-m");
        $work_times = WorkTime::where('user_id', $user->id)->where('date', 'like', $current_month . '%')->get();
        $fixed_time = FixedTime::first();
        $paid_leaves = PaidLeave::where('user_id', $user->id)->first();

        $count_paid_leaves = $work_times->where('work_type_id', 5)->count();

        return view('input.input', [
            'today' => $today,
            'month' => $month,
            'dt' => $dt,
            'daysInMonth' => $daysInMonth,
            'work_times' => $work_times,
            'fixed_time' => $fixed_time,
            'paid_leaves' => $paid_leaves,
            'user' => $user,
            'description' => $description,
            'count_paid_leaves' => $count_paid_leaves,
            'holidays' => $holidays,
        ]);
    }

}
