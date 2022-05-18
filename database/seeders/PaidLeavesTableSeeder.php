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
            ],
            [
                'user_id' => '202204010001',
            ],
            [
                'user_id' => '202204010004',
            ],
            [
                'user_id' => '202204010004',
            ],
            [
                'user_id' => '202204010005',
            ],  
            [
                'user_id' => '202204010005',
            ],  
        ];
        DB::table('paid_leaves')->insert($params);   
    }
}
