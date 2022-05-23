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
                'user_id' => '202104010001',
                'work_type_id' => 1,
                'date' => '2022-05-02',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
                'over_time' => '00:00',
            ],
            [
                'user_id' => '202104010001',
                'work_type_id' => 1,
                'date' => '2022-05-03',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
                'over_time' => '00:00',
            ],
            [
                'user_id' => '202104010001',
                'work_type_id' => 1,
                'date' => '2022-05-04',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
                'over_time' => '00:00',
            ],
            [
                'user_id' => '202104010001',
                'work_type_id' => 1,
                'date' => '2022-05-05',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
                'over_time' => '00:00',
            ],
            [
                'user_id' => '202104010001',
                'work_type_id' => 1,
                'date' => '2022-05-06',
                'start_time' => '09:30',
                'left_time' => '18:00',
                'rest_time' => '00:45',
                'over_time' => '00:00',
            ],
            [
                'user_id' => '202004010001',
                'work_type_id' => 3,
                'date' => '2022-04-04',
                'start_time' => '10:00',
                'left_time' => '18:05',
                'rest_time' => '00:45',
                'over_time' => '00:00',
            ],
            [
                'user_id' => '202004010001',
                'work_type_id' => 4,
                'date' => '2022-04-05',
                'start_time' => '09:25',
                'left_time' => '16:05',
                'rest_time' => '00:45',
                'over_time' => '00:00',
            ],
            [
                'user_id' => '202004010001',
                'work_type_id' => 1,
                'date' => '2022-04-06',
                'start_time' => '09:25',
                'left_time' => '18:05',
                'rest_time' => '00:45',
                'over_time' => '00:00',
            ],
            [
                'user_id' => '202004010001',
                'work_type_id' => 1,
                'date' => '2022-04-07',
                'start_time' => '09:28',
                'left_time' => '18:03',
                'rest_time' => '00:45',
                'over_time' => '00:00',
            ],
            [
                'user_id' => '202004010001',
                'work_type_id' => 2,
                'date' => '2022-04-08',
                'start_time' => null,
                'left_time' => null,
                'rest_time' => null,
                'over_time' => null,
            ],
        ];
        DB::table('work_times')->insert($params);
    }
}
