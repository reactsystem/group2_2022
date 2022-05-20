<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use App\Models\AddPaidLeave;
use App\Models\PaidLeave;

class AddPaidLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:addPaidLeave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '有給付与日が来たら有給を付与する&二年経過した分はソフトデリートします。';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //現在
        $now = Carbon::now();
        $nowDate = $now->format('Y-m-d');

        //2年後
        $twoYearsLater = $now->copy()->addYear(2);

        $users = User::all();

        foreach($users as $user){

            // 入社日
            $joining = $user->joining;
            $joining_day = new Carbon($joining);
            // 入社日から現在までの時差
            $diff_months = $joining_day->diffInMonths($now);
            
            // 初期に付与される有給休暇、付与される期間
            $initial_span = 6;
            $added_span = 12;
            $diff_years = 0.5;

            // 勤続年数が40年までと想定、月→年に変換
            $i = 0;
            while($initial_span<=480){
                if($initial_span <= $diff_months && $diff_months <= $initial_span+$added_span){
                    $diff_years += $i;
                }
                $i++;
                $initial_span += $added_span;
            }
            
            // 勤続年数によって今期付与される有給休暇(1年ごと)
            if($diff_months % 12 === 6){
                $provided_leave = AddPaidLeave::select('add_days')->where('years', '=', $diff_years)->first()->add_days;
                for($m=0; $m<=0; $m++){
                    PaidLeave::insert([
                        'user_id' => $user->id,
                        'left_days' => $provided_leave,
                        'created_at' => $now,
                        'expire_date' => $twoYearsLater
                    ]);
                }
            }else{
                $provided_leave = 0;
            }

            // 社員1人分の有給休暇
            $personal_leaves=PaidLeave::where('user_id', $user->id)->get();

            //expire_dateに達したデータをソフトデリート
            for($j=0; $j<count($personal_leaves); $j++){
                $expire_date = new Carbon($personal_leaves[$j]->expire_date);
                $expired_date = $expire_date->format('Y-m-d');
                if($nowDate === $expired_date){
                    $personal_leaves[0]->delete();
                }
            }
        }
    }
}
