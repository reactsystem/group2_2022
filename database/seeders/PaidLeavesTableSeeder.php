<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaidLeavesTableSeeder extends Seeder
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
                'user_id' => '202204010001',
                'left_days' => 10,
            ],
            [
                'user_id' => '202204010002',
                'left_days' => 10,
            ],
            [
                'user_id' => '202004010001',
                'left_days' => 10,
            ],
            [
                'user_id' => '202204010003',
                'left_days' => 10,
            ],
            [
                'user_id' => '201904010003',
                'left_days' => 10,
            ],

            [
                'user_id' => '202204010004',
                'left_days' => 10,
            ],
            
            [
                'user_id' => '202204010005',
                'left_days' => 10,
            ],
            [
                'user_id' => '201704010001',
                'left_days' => 10,
            ],

        ];

        $now = Carbon::now();
        foreach ($params as $param) {
            $param['created_at'] = $now;
            $param['updated_at'] = $now;
            DB::table('paid_leaves')->insert($param);
        }
    }
}
