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
        $now = Carbon::yesterday();
        $nowDate = $now->format('Y-m-d');

        //2年後
        $twoYearsLater = $now->copy()->addYear(2);

        $users = User::whereNull('leaving')->orWhere('leaving', '>=' , $now->toDateString())->get();

        foreach($users as $user){
            // 入社日
            $joining = new Carbon($user->joining);
            // 入社日から現在までの時差(勤続月数)
            $diff_months = $joining->diffInMonths($now);

            // 今日が入社日と違う日付、もしくは月末以外の場合
            if ($joining->day === $now->day ||
                ($joining->day >= $now->lastOfMonth()->day && $now->day === $now->lastOfMonth()->day))
            {
                // AddPaidLeavesテーブルのレコードを取得
                $add_paid_leaves = AddPaidLeave::all();
            
                // AddPaidLeavesテーブルに登録されている最高年数のレコードを取得
                $max_apl = AddPaidLeave::max('years');
                $max_apl = AddPaidLeave::where('years', $max_apl)->first();
            
                // 付与月かを探索
                foreach ($add_paid_leaves as $apl)
                {
                    // 付与月の場合
                    if ((int)($apl->years * 12) === $diff_months)
                    {
                        // 有給付与
                        PaidLeave::insert([
                            'user_id' => $user->id,
                            'left_days' => $apl->add_days,
                            'created_at' => $now,
                            'expire_date' => $twoYearsLater
                        ]);
                        break;
                    }
                    // AddPaidLeavesテーブルに登録されている年数を超えた場合
                    elseif ((int)($max_apl->years * 12) <= $diff_months)
                    {
                        $calc_month = ($diff_months - $max_apl->years * 12) % 12;
                        if ($calc_month === 0)
                        {
                            // 有給付与
                            PaidLeave::insert([
                                'user_id' => $user->id,
                                'left_days' => $max_apl->add_days,
                                'created_at' => $now,
                                'expire_date' => $twoYearsLater
                            ]);
                            break;
                        }
                    }
                }
            }

            // 社員1人分の有給休暇
            $personal_leaves=PaidLeave::where('user_id', $user->id)->get();

            //expire_dateに達したデータをソフトデリート
            for($j=0; $j<count($personal_leaves); $j++){
                $expire_date = new Carbon($personal_leaves[$j]->expire_date);
                $expired_date = $expire_date->format('Y-m-d');
                if($nowDate === $expired_date){
                    $personal_leaves[$j]->where('expire_date', $nowDate)->delete();
                }
            }
        }
    }
}
