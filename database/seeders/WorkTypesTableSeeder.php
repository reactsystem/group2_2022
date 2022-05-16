<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WorkTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params =
        [
            [
                'name' => '出勤',
            ],
            [
                'name' => '欠勤',
            ],
            [
                'name' => '遅刻',
            ],
            [
                'name' => '早退',
            ],
            [
                'name' => '有給休暇',
            ],
            [
                'name' => '特別休暇',
            ],
            [
                'name' => '遅刻/早退'
            ],
        ];

        $now = Carbon::now();
        foreach ($params as $param) {
            $param['created_at'] = $now;
            $param['updated_at'] = $now;
            DB::table('work_types')->insert($param);
        }
    }
}
