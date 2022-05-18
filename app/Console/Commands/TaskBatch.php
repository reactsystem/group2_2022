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

        // idは部署勤怠管理フォームからとってくる。今回は仮に「201704010001」とする。
        $id = 201704010001;
        $joining = User::find($id)->joining;
        $query = User::find($id);
        $joining_day = new Carbon($joining, 'UTC');
        $diff_months = $joining_day->diffInMonths($now);
        $initial_span = 6;
        $added_span = 12;
        $diff_years = 0;
        $limit_leaves = 40;

        $i = 0;
        while($initial_span<=480){
            if($initial_span <= $diff_months && $diff_months<= $initial_span+$added_span){
                $diff_years = 0.5+$i;
            }
            $i++;
            $initial_span += $added_span;
        }
        

        $leaves = AddPaidLeave::select('add_days')->where('years', '<=', $diff_years)->get();
        $i=0;
        while($i<count($leaves)){
            $added_leave[] = $leaves[$i]->add_days;
            $i++;
        }
        $provided_leave = end($added_leave);

        $total_leave = array_sum($added_leave);
        if($total_leave <= $limit_leaves){
            $total_leave = array_sum($added_leave);
        }else{
            $total_leave = $limit_leaves;
        }
        $remain_leave = $query->paidLeave->left_days;

        $paid_leave = PaidLeave::where('user_id', $id)->first();
        if($paid_leave->left_days <= $limit_leaves){
            $paid_leave->left_days = $remain_leave + $provided_leave;
        }else{
            $paid_leave->left_days = $limit_leaves;
        }
        $paid_leave->save();
    }
}
