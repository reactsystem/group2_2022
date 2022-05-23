<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApplicationsTableSeeder extends Seeder
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
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 1,
                'reason' => '私用のため',
            ],
            
            [
                'user_id' => '202104010001',
                'application_type_id' => 2,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 4,
                'date' => '2022-05-27',
                'start_time' => '18:15:00',
                'end_time' => '19:00:00',
                'status' => 0,
                'reason' => '私用のため',
            ],
        
            [
                'user_id' => '202104010002',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 2,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010003',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010004',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202004010001',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202004010002',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202004010003',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202004010001',
                'application_type_id' => 5,
                'date' => '2022-04-04',
                'start_time' => '09:30:00',
                'status' => 0,
                'reason' => '打刻忘れ',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202004010004',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202004010005',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202004010001',
                'application_type_id' => 1,
                'date' => '2022-06-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202004010002',
                'application_type_id' => 1,
                'date' => '2022-07-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202004010003',
                'application_type_id' => 1,
                'date' => '2022-08-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
            
            [
                'user_id' => '202104010001',
                'application_type_id' => 3,
                'date' => '2022-05-27',
                'status' => 0,
                'reason' => '私用のため',
            ],
        ];

        $now = Carbon::now();
        foreach ($params as $param) {
            $param['created_at'] = $now;
            DB::table('applications')->insert($param);
        }
    }
}
