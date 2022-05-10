<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\WorkType;
use App\Models\FixedTime;
use App\Models\PaidLeave;
use Carbon\Carbon;

class InputFormController extends Controller
{
    public function show(){
        Carbon::setLocale('ja');
        $today = Carbon::createFromDate();
        $dt = Carbon::createFromDate();

        $dt->startOfMonth(); //今月の最初の日
        $dt->timezone = 'Asia/Tokyo'; //日本時刻で表示
        $daysInMonth = $dt->daysInMonth; //今月は何日まであるか

        $work_times = WorkTime::where('user_id', '202204010001')->get();
        $fixed_time = FixedTime::first();
        $paid_leaves = PaidLeave::where('user_id', '202204010001')->first();

        return view('input.input', [
            'today' => $today,
            'dt' => $dt,
            'daysInMonth' => $daysInMonth,
            'work_times' => $work_times,
            'fixed_time' => $fixed_time,
            'paid_leaves' => $paid_leaves,
        ]);
    }
}
