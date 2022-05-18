<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use app\Console\Commands\TaskBatch;
use App\Models\AddPaidLeave;
use App\Models\PaidLeave;
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
        $now = Carbon::now('UTC');

        $users = User::all();
        foreach($users as $user){
            $joining = $user->joining;
            $joining_date = new Carbon($joining, 'UTC');
            $joining_day = $joining_date->day;
            $diff_months = $joining_date->diffInMonths($now);
            
            if($diff_months % 12 === 6){
                $schedule->command('update')->monthlyOn($joining_day, '00:00');
            }
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
