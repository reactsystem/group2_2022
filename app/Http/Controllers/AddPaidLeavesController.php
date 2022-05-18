<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AddPaidLeave;
use App\Models\PaidLeave;
use App\Models\FixedTime;
use App\Models\WorkTime;
use App\Models\WorkType;
use Yasumi\Yasumi;
use Carbon\CarbonPeriod;

class AddPaidLeavesController extends Controller
{

    public function index($id)
    {
        $now = Carbon::now('UTC');

        // idは部署勤怠管理フォームからとってくる。今回は仮に「201704010001」とする。
        $id = 201704010001;
        $joining = User::find($id)->joining;
        $user = User::find($id);
        $joining_day = new Carbon($joining, 'UTC');
        $diff_months = $joining_day->diffInMonths($now);
        $initial_span = 6;
        $added_span = 12;
        $diff_years = 0;
        $day_leaves = 0;
        $limit_leaves = 40;

        $i = 0;
        while($initial_span<=480){
            if($initial_span <= $diff_months && $diff_months<= $initial_span+$added_span){
                $diff_years = 0.5+$i;
            }
            $i++;
            $initial_span += $added_span;
        }
        
        //有給休暇取得日数
        $leaves = AddPaidLeave::select('add_days')->where('years', '<=', $diff_years)->get();
        $i=0;
        while($i<count($leaves)){
            $added_leave[] = $leaves[$i]->add_days;
            $i++;
        }
        $provided_leave = end($added_leave);

        $total_leave = array_sum($added_leave);
        if($total_leave <= 40){
            $total_leave = array_sum($added_leave);
        }else{
            $total_leave = 40;
        }

        //有給休暇残り日数
        $remain_leave = $user->paidLeave->left_days;

        //getパラメータに当年月をもたせる
        $thisMonth = $now->format('Y-m');

        $fixed_time = FixedTime::first();

        //所定時間を求める
        $start_time = new Carbon($fixed_time->start_time);
        $left_time = new Carbon($fixed_time->left_time);
        $rest_time = new Carbon($fixed_time->rest_time);
        $rest_time = $rest_time->minute;

        $difftime = $left_time->diffInMinutes($start_time);
        $workTime = $difftime-$rest_time;
        
        //祝日、土日を除く
        $startDate = new Carbon($thisMonth.'-01');
        $endDate   = new Carbon($thisMonth);
        $endDate->endOfMonth();

        // 土日を除く平日を取得
        $days = (int)$startDate->diffInDaysFiltered(
            function (Carbon $date) {
                return $date->isWeekday();
            }, $endDate
        );

        // 祝日を取得
        $holidays = Yasumi::create('Japan', $now->format('Y'), 'ja_JP');

        $holidaysInBetweenDays = $holidays->between(
            \DateTime::createFromFormat('m/d/Y', $startDate->format('m/d/Y')),
            \DateTime::createFromFormat('m/d/Y', $endDate->format('m/d/Y'))
        );

        $numberOfHoliday = 0;
        foreach ($holidaysInBetweenDays as $holiday) {
            if ((new Carbon($holiday))->isWeekend() === false) {
                $numberOfHoliday++;
            }
        }

        // さらに祝日の数を引いた平日の日数を取得
        $numberOfDay = $days - $numberOfHoliday;

        $workTime = $workTime * $numberOfDay;
        
        //所定時間
        $workMinute = $workTime%60;
        $workHour= floor($workTime/60);
        $fixedWorkTime = $workHour.':'.$workMinute;

        //休憩時間 月をwhereで追加
        $restTimes = WorkTime::whereIn('user_id', function($query) use($id){
            $query->from('users')
            ->select('id')
            ->where('id', $id);
        })->get(['rest_time']);

        foreach($restTimes as $time){
            $t = explode(":", $time);
            $t = str_replace('"', '', $t);
            $h = (int)$t[1];
            $m = (int)$t[2];
            $restTime[] = ($h*60*60) + ($m*60);
        }
        $restTimeSec = array_sum($restTime);
        $restTimeHour = floor($restTimeSec/3600);
        $restTimeMin = floor(($restTimeSec%3600)/60);
        $totalRestTime = $restTimeHour.':'.$restTimeMin;

        //時間外 月をwhereで追加
        $overTimes = WorkTime::whereIn('user_id', function($query) use($id){
            $query->from('users')
            ->select('id')
            ->where('id', $id);
        })->get(['over_time']);

        foreach($overTimes as $time){
            $t = explode(":", $time);
            $t = str_replace('"', '', $t);
            $h = (int)$t[1];
            $m = (int)$t[2];
            $overTime[] = ($h*60*60) + ($m*60);
        }
        $overTimeSec = array_sum($overTime);
        $overTimeHour = floor($overTimeSec/3600);
        $overTimeMin = floor(($overTimeSec%3600)/60);
        $totalOverTime = $overTimeHour.':'.$overTimeMin;

        //労働時間

        //始業時間 月をwhereで追加
        $startTimes = WorkTime::whereIn('user_id', function($query) use($id){
            $query->from('users')
            ->select('id')
            ->where('id', $id);
        })->get(['start_time']);

        foreach($startTimes as $time){
            $t = explode(":", $time);
            $t = str_replace('"', '', $t);
            $h = (int)$t[1];
            $m = (int)$t[2];
            $startTime[] = ($h*60*60) + ($m*60);
        }
        $startTimeSec = array_sum($startTime);
        
        //終業時間 月をwhereで追加
        $leftTimes = WorkTime::whereIn('user_id', function($query) use($id){
            $query->from('users')
            ->select('id')
            ->where('id', $id);
        })->get(['left_time']);

        foreach($leftTimes as $time){
            $t = explode(":", $time);
            $t = str_replace('"', '', $t);
            $h = (int)$t[1];
            $m = (int)$t[2];
            $leftTime[] = ($h*60*60) + ($m*60);
        }
        $leftTimeSec = array_sum($leftTime);
        $workTimeSec = $leftTimeSec - $startTimeSec - $restTimeSec;
        $workTimeHour = floor($workTimeSec/3600);
        $workTimeMin = floor(($workTimeSec%3600)/60);
        $totalWorkTime = $workTimeHour.':'.$workTimeMin;
        
        $period = CarbonPeriod::create($startDate, $endDate)->toArray();

        foreach($period as $day){
            $userInfo[] = WorkTime::where('user_id', $id)->where('date', $day)->first();
        }
        
        $workTypes = WorkType::get();

        return view('personal_management', compact('total_leave', 'remain_leave', 'user', 'fixedWorkTime', 'totalRestTime', 'totalOverTime', 'totalWorkTime', 'period', 'userInfo', 'workTypes'));
    }
}
