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
				'user_id' => '202004010001',
				'left_days' => 3,
				'expire_date' => '2023-10-01',
				'created_at' => '2021-10-01',
			],
        ];
		
        foreach ($params as $param) {
            DB::table('paid_leaves')->insert($param);
        }
    }
}
