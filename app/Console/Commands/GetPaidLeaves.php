<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\PaidLeave;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetPaidLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getPaidLeave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '有給休暇を取得して有給休暇残り日数を1減らします。';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $nowDate = $now->format('Y-m-d');
        $approvePaidLeaves = Application::where('application_type_id', 1)->where('date', $nowDate)->where('status', 1)->get();
        foreach($approvePaidLeaves as $approvePaidLeave){
            $paid_leave = PaidLeave::where('user_id', $approvePaidLeave->user_id)->oldest('expire_date')->first();
            if(!empty($paid_leave->left_days) && $paid_leave->left_days > 0){
                $paid_leave->left_days = $paid_leave->left_days-1;
                $paid_leave->updated_at = $now;
                $paid_leave->save();
            }
        }
    }
}
