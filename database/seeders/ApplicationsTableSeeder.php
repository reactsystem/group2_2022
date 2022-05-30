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
			/* 承認済み ------------------*/
			[	// 有給休暇
				'user_id' => '202004010001',
				'application_type_id' => 1,
				'date' => '2022-05-10',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-04-15 10:00:00',
				'updated_at' => '2022-04-18 10:00:00',
			],
			// バッチ処理(リハ用)
			[	// 有給休暇
				'user_id' => '202004010001',
				'application_type_id' => 1,
				'date' => '2022-05-30',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-04-15 10:00:00',
				'updated_at' => '2022-04-18 10:00:00',
			],
			// バッチ処理(本番用)
			[	// 有給休暇
				'user_id' => '202004010001',
				'application_type_id' => 1,
				'date' => '2022-05-31',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-04-15 10:00:00',
				'updated_at' => '2022-04-18 10:00:00',
			],
			[	// 特別休暇
				'user_id' => '202004010001',
				'application_type_id' => 2,
				'date' => '2022-05-11',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-04-15 10:05:00',
				'updated_at' => '2022-04-18 10:05:00',
			],
			[	// 休日出勤
				'user_id' => '202004010001',
				'application_type_id' => 3,
				'date' => '2022-05-01',
				'status' => 2,
				'reason' => '私用のため',
				'created_at' => '2022-04-28 14:00:00',
				'updated_at' => '2022-04-28 16:00:00',
			],
			[	// 休日出勤
				'user_id' => '202004010001',
				'application_type_id' => 3,
				'date' => '2022-05-03',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-04-28 14:00:00',
				'updated_at' => '2022-04-28 16:00:00',
			],
			[	// 休日出勤
				'user_id' => '202004010001',
				'application_type_id' => 3,
				'date' => '2022-05-04',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-04-28 14:05:00',
				'updated_at' => '2022-04-28 16:05:00',
			],
			[	// 休日出勤
				'user_id' => '202004010001',
				'application_type_id' => 3,
				'date' => '2022-05-05',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-04-28 14:10:00',
				'updated_at' => '2022-04-28 16:10:00',
			],
			[	// 休日出勤
				'user_id' => '202004010001',
				'application_type_id' => 3,
				'date' => '2022-05-07',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-04-28 14:15:00',
				'updated_at' => '2022-04-28 16:15:00',
			],
			[	// 休日出勤
				'user_id' => '202004010001',
				'application_type_id' => 3,
				'date' => '2022-05-08',
				'status' => 3,
				'reason' => '私用のため',
				'created_at' => '2022-04-28 14:20:00',
				'updated_at' => '2022-04-28 16:20:00',
			],
			[	// 時間外勤務
				'user_id' => '202004010001',
				'application_type_id' => 4,
				'date' => '2022-05-04',
				'start_time' => '18:15:00',
				'end_time' => '19:30:00',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-05-04 15:00:00',
				'updated_at' => '2022-05-04 17:00:00',
			],
			[	// 打刻修正
				'user_id' => '202004010001',
				'application_type_id' => 5,
				'date' => '2022-05-02',
				'start_time' => '09:30:00',
				'end_time' => '18:00:00',
				'status' => 1,
				'reason' => '私用のため',
				'created_at' => '2022-05-04 15:05:00',
				'updated_at' => '2022-05-04 17:05:00',
			],
			/*============================*/

			/* 申請中 --------------------*/
			[	// 有給休暇
				'user_id' => '202004010001',
				'application_type_id' => 1,
				'date' => '2022-05-16',
				'status' => 0,
				'reason' => '私用のため',
				'created_at' => '2022-05-30 10:00:00',
				'updated_at' => '2022-05-30 10:00:00',
			],
			[	// 有給休暇
				'user_id' => '202004010001',
				'application_type_id' => 1,
				'date' => '2022-06-01',
				'status' => 0,
				'reason' => '私用のため',
				'created_at' => '2022-05-30 10:05:00',
				'updated_at' => '2022-05-30 10:05:00',
			],
			[	// 打刻修正
				'user_id' => '202004010001',
				'application_type_id' => 5,
				'date' => '2022-05-17',
				'start_time' => '09:30:00',
				'end_time' => '18:00:00',
				'status' => 0,
				'reason' => '私用のため',
				'created_at' => '2022-05-30 10:10:00',
				'updated_at' => '2022-05-30 10:10:00',
			],
			[	// 時間外勤務
				'user_id' => '202004010001',
				'application_type_id' => 4,
				'date' => '2022-05-31',
				'start_time' => '18:15:00',
				'end_time' => '19:30:00',
				'status' => 0,
				'reason' => '私用のため',
				'created_at' => '2022-05-30 10:15:00',
				'updated_at' => '2022-05-30 10:15:00',
			],
			[	// 特別休暇
				'user_id' => '202004010001',
				'application_type_id' => 2,
				'date' => '2022-06-02',
				'status' => 0,
				'reason' => '私用のため',
				'created_at' => '2022-05-30 10:20:00',
				'updated_at' => '2022-05-30 10:20:00',
			],
			[	// 休日出勤
				'user_id' => '202004010001',
				'application_type_id' => 3,
				'date' => '2022-06-04',
				'status' => 0,
				'reason' => '私用のため',
				'created_at' => '2022-05-30 10:25:00',
				'updated_at' => '2022-05-30 10:25:00',
			],
			/*============================*/
        ];

        $now = Carbon::now();
        foreach ($params as $param) {
            DB::table('applications')->insert($param);
        }
    }
}
