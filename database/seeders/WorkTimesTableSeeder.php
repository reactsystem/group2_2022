<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WorkTimesTableSeeder extends Seeder
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
                'work_type_id' => 1,
                'date' => '2022-05-02',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
            ],
            [
                'user_id' => '202204010001',
                'work_type_id' => 1,
                'date' => '2022-05-03',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
            ],
            [
                'user_id' => '202204010001',
                'work_type_id' => 1,
                'date' => '2022-05-04',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
            ],
            [
                'user_id' => '202204010001',
                'work_type_id' => 1,
                'date' => '2022-05-05',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
            ],
            [
                'user_id' => '202204010001',
                'work_type_id' => 1,
                'date' => '2022-05-06',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
            ],

        ];
        DB::table('work_times')->insert($params);
    }
}
