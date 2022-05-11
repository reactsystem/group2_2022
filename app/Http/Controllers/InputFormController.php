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
use Laravel\Ui\Presets\React;

class InputFormController extends Controller
{
    public function show(Request $request){

        // 当日の日付表示と月表示を取得する
        Carbon::setLocale('ja');
        $today = Carbon::createFromDate();

        if (isset($request->month)) {
            $month = $request->month;
        } else {
            $month = Carbon::createFromDate();
        }
        
        // 今月の最初の日付を取得する
        $dt = Carbon::createFromDate();
        $dt->startOfMonth(); //今月の最初の日
        $dt->timezone = 'Asia/Tokyo'; //日本時刻で表示
        $daysInMonth = $dt->daysInMonth; //今月は何日まであるか

        $user = Auth::user();
        $work_times = WorkTime::where('user_id', $user->id)->get();
        $fixed_time = FixedTime::first();
        $paid_leaves = PaidLeave::where('user_id', $user->id)->first();

        return view('input.input', [
            'today' => $today,
            'month' => $month,
            'dt' => $dt,
            'daysInMonth' => $daysInMonth,
            'work_times' => $work_times,
            'fixed_time' => $fixed_time,
            'paid_leaves' => $paid_leaves,
            'user' => $user,
        ]);
    }

    public function add(Request $request){
        $date = date("Y-m-d");
        $time = date("H:i:s");

        // 出勤処理
        if (isset($request->start_time)){
            $work_time = new WorkTime;
            $work_time->user_id = $request->user_id;
            $work_time->work_type_id = 1;
            $work_time->date = $date;
            $work_time->start_time = $time;
            $work_time->over_time = '00:00:00';
            $work_time->description = $request->description;
            $work_time->save();
        }

        // 退勤処理
        if (isset($request->left_time)){
            WorkTime::where('user_id', $request->user_id)->where('date', date("Y-m-d"))->update([
                'left_time' => $time,
                'rest_time' => '00:45:00',
                'description' => $request->description,
            ]);
        }
        return redirect('/');
    }

    public function showMonth(Request $request){

        // 当日の日付表示と月表示を取得する
        Carbon::setLocale('ja');
        $today = Carbon::createFromDate();
        $month = $request->month;
        
        // 今月の最初の日付を取得する
        $dt = $request->month;
        $dt->startOfMonth(); //今月の最初の日
        $dt->timezone = 'Asia/Tokyo'; //日本時刻で表示
        $daysInMonth = $dt->daysInMonth; //今月は何日まであるか

        $user = Auth::user();
        $work_times = WorkTime::where('user_id', $user->id)->get();
        $fixed_time = FixedTime::first();
        $paid_leaves = PaidLeave::where('user_id', $user->id)->first();

        return view('input.input', [
            'today' => $today,
            'month' => $month,
            'dt' => $dt,
            'daysInMonth' => $daysInMonth,
            'work_times' => $work_times,
            'fixed_time' => $fixed_time,
            'paid_leaves' => $paid_leaves,
            'user' => $user,
        ]);
    }

}
