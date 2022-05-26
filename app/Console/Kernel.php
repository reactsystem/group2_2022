<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\User;
use Carbon\Carbon;

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
        $now = Carbon::now();

        $users = User::all();
        foreach($users as $user){
            $joining = $user->joining;
            $joining_date = new Carbon($joining);
            $joining_day = $joining_date->day;
            $diff_months = $joining_date->diffInMonths($now);
            
            //月の入社日の日で更新(新入社員6ヶ月後、以降1年ごと)
            // if($diff_months % 12 === 6){
            //     $schedule->command('addPaidLeave')->monthlyOn($joining_day, '00:00');
            // }
            
            // テスト用
            $schedule->command('addPaidLeave')->everyMinute();

            //1日に一回有給休暇取得実行
            // $schedule->command('getPaidLeave')->daily();

            // テスト用
            $schedule->command('getPaidLeave')->everyMinute();
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
