<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PaidLeave;
use App\Models\AddPaidLeave;

class AddPaidLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-paid-leaves';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            // 勤続年数を計算
            $user_years = (strtotime("now") - strtotime($user->joining)) / 86400 / 365 ;

            if ($user_years == 0.5) {
                $add_paid_leave = AddPaidLeave::find(1);
                $user->paidLeave->left_days += $add_paid_leave->add_days;
            }
            if ($user_years == 1.5) {
                $add_paid_leave = AddPaidLeave::find(2);
                $user->paidLeave->left_days += $add_paid_leave->add_days;
            }
            if ($user_years == 2.5) {
                $add_paid_leave = AddPaidLeave::find(3);
                $user->paidLeave->left_days += $add_paid_leave->add_days;
            }
            if ($user_years == 3.5) {
                $add_paid_leave = AddPaidLeave::find(4);
                $user->paidLeave->left_days += $add_paid_leave->add_days;
            }
            if ($user_years == 4.5) {
                $add_paid_leave = AddPaidLeave::find(5);
                $user->paidLeave->left_days += $add_paid_leave->add_days;
            }
            if ($user_years == 5.5) {
                $add_paid_leave = AddPaidLeave::find(6);
                $user->paidLeave->left_days += $add_paid_leave->add_days;
            }
            if ($user_years == 6.5) {
                $add_paid_leave = AddPaidLeave::find(7);
                $user->paidLeave->left_days += $add_paid_leave->add_days;
            }

            // TODO:7.5年以降の処理を追加,有給付与上限の場合は超えた分を追加しない設定を追加

        }

    }
}
