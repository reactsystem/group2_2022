<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AddPaidLeave;
use App\Models\PaidLeave;

class TaskBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update';

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
        $now = Carbon::now('UTC');

        $users = User::all();
        foreach($users as $user){

            // 入社日
            $joining = $user->joining;
            $joining_day = new Carbon($joining, 'UTC');

            // 入社日から現在までの時差
            $diff_months = $joining_day->diffInMonths($now);

            // 初期に付与される有給休暇、付与される期間、上限
            $initial_span = 6;
            $added_span = 12;
            $diff_years = 0;
            $limit_leaves = 40;
    
            // 勤続年数が40年までと想定
            $i = 0;
            while($initial_span<=480){
                if($initial_span <= $diff_months && $diff_months<= $initial_span+$added_span){
                    $diff_years = 0.5+$i;
                }
                $i++;
                $initial_span += $added_span;
            }
            
            // 勤続年数によって、今までに付与された有給休暇の全てを配列で取得
            $leaves = AddPaidLeave::select('add_days')->where('years', '<=', $diff_years)->get();

            $i=0;
            while($i<count($leaves)){
                $added_leave[] = $leaves[$i]->add_days;
                $i++;
            }

            // 社員が今期に付与される有給休暇
            $provided_leave = end($added_leave);
    
            // 社員が付与された有給休暇全てを数字で取得 必要ないかも <<<<<<<<<<
            $total_leave = array_sum($added_leave);


            if($total_leave <= $limit_leaves){
                $total_leave = array_sum($added_leave);
            }else{
                $total_leave = $limit_leaves;
            }
            // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

            //社員の有給休暇残り日数
            $remain_leave = $user->paidLeave->left_days;
    
            $paid_leave = PaidLeave::where('user_id', $user->id)->first();

            //勤続年数によってPaidLeaveテーブル更新
            if($paid_leave->left_days <= $limit_leaves){
                $paid_leave->left_days = $remain_leave + $provided_leave;
                if($paid_leave->left_days === null){
                    $paid_leave->created_at = $now;
                }else{
                    $paid_leave->updated_at = $now;
                }
            }else{
                $paid_leave->left_days = $limit_leaves;
                $paid_leave->updated_at = $now;
            }
            $paid_leave->save();
        }
    }
}
