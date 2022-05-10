<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AddPaidLeavesTableSeeder extends Seeder
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
                'years' => 0.5,
                'add_days' => 10,
            ],
            [
                'years' => 1.5,
                'add_days' => 11,
            ],
            [
                'years' => 2.5,
                'add_days' => 12,
            ],
            [
                'years' => 3.5,
                'add_days' => 14,
            ],
            [
                'years' => 4.5,
                'add_days' => 16,
            ],
            [
                'years' => 5.5,
                'add_days' => 18,
            ],
            [
                'years' => 6.5,
                'add_days' => 20,
            ],

        ];

        $now = Carbon::now();
        foreach ($params as $param) {
            $param['created_at'] = $now;
            $param['updated_at'] = $now;
            DB::table('add_paid_leaves')->insert($param);
        }
    }
}
