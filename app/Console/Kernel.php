<?php

namespace App\Console;

use App\Models\AddPaidLeave;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\User;
use Carbon\Carbon;
use App\Models\FixedTime;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 発表用
        $schedule->command('command:addPaidLeave')->everyMinute();
        $schedule->command('command:getPaidLeave')->everyMinute();
        
        // 本番用
        $now = Carbon::now();
        $users = User::whereNull('leaving')->orWhere('leaving', '>=' , $now->toDateString())->get();
        foreach($users as $user){
            $joining = $user->joining;
            $joining_date = new Carbon($joining);
            $joining_day = $joining_date->day;
            $diff_months = $joining_date->diffInMonths($now);

            // 有給付与年数最小値
            $min_year = AddPaidLeave::min('years');

            // 2つ目の有給付与年数取得
            $sec_year = AddPaidLeave::find(2)->years;
            
            
            // 月の入社日の日で更新(新入社員6ヶ月後、以降1年ごと)
            // if($min_year != 0){
            //     if($diff_months % 12*(int)$min_year == 0){
            //         $schedule->command('command:addPaidLeave')->monthlyOn($joining_day, '00:00');
            //     }
            // }elseif($min_year == 0){
            //     if($diff_months % 12*(int)$sec_year == 0){
            //         $schedule->command('command:addPaidLeave')->monthlyOn($joining_day, '00:00');
            //     }
            // }
            
            // 1日に一回有給休暇取得実行
            // $schedule->command('command:getPaidLeave')->dailyAt(FixedTime::first()->start_time);           
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
