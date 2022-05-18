<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
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
        $now = Carbon::now();

        $users = User::all();
        foreach($users as $user){

            // 入社日
            $joining = $user->joining;
            $joining_day = new Carbon($joining);
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
            if(!$leaves->isEmpty()){
                while($i<count($leaves)){
                    $added_leave[] = $leaves[$i]->add_days;
                    $i++;
                }
            }else{
                $added_leave = 0;
            }
            
            // 社員が今期に付与される有給休暇
            if($added_leave){
                $provided_leave = end($added_leave);
            }else{
                $provided_leave = 0;
            }
            
            // 社員の有給休暇残り日数、2年で2年前までの分リセット
            $personal_leaves=PaidLeave::where('user_id', $user->id)->get();
            for($j=0; $j<count($personal_leaves); $j++){
                if(empty($personal_leaves[$j]->created_at)){
                    $personal_leaves[$j]->left_days = $provided_leave;
                    $personal_leaves[$j]->created_at = $now;
                    $personal_leaves[$j]->save();
                }else{
                    $iniAddedDay = new Carbon($personal_leaves[$j]->created_at);
                    $diffIniMonth = $iniAddedDay->diffInMonths($now);   
                    if($diffIniMonth === 12){
                        $personal_leaves[1]->left_days = $personal_leaves[0]->left_days + $provided_leave;
                        $personal_leaves[1]->save();
                        PaidLeave::insert([
                            'user_id' => $personal_leaves[0]->id,
                            'left_days' => $personal_leaves[0]->left_days + $provided_leave,
                            'created_at' => $now,
                        ]);
                        $personal_leaves[0]->delete();
                    }
                }
            }
        }
    }
}
