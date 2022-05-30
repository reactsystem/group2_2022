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
			// 2022/04 01~02week
			[	// 通常出勤
				'user_id' => '202004010001',
				'work_type_id' => 1,
				'date' => '2022-05-02',
				'start_time' => '09:30',
				'left_time' => '18:00',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '通常出勤',
			],
			[	// 通常出勤（丸め確認）
				'user_id' => '202004010001',
				'work_type_id' => 1,
				'date' => '2022-05-03',
				'start_time' => '09:20',
				'left_time' => '18:10',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '規定時間外に打刻された場合',
			],
			[	// 残業
				'user_id' => '202004010001',
				'work_type_id' => 1,
				'date' => '2022-05-04',
				'start_time' => '09:30',
				'left_time' => '19:00',
				'rest_time' => '01:00',
				'over_time' => '00:45',
				'description' => '残業',
			],
			[	// 遅刻
				'user_id' => '202004010001',
				'work_type_id' => 3,
				'date' => '2022-05-05',
				'start_time' => '10:00',
				'left_time' => '18:00',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '遅刻',
			],
			[	// 早退
				'user_id' => '202004010001',
				'work_type_id' => 4,
				'date' => '2022-05-06',
				'start_time' => '09:30',
				'left_time' => '16:00',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '早退',
			],
			[	// 遅刻/早退
				'user_id' => '202004010001',
				'work_type_id' => 5,
				'date' => '2022-05-07',
				'start_time' => '10:00',
				'left_time' => '15:00',
				'rest_time' => '00:00',
				'over_time' => '00:00',
				'description' => '遅刻かつ早退',
			],
			[	// 欠勤
				'user_id' => '202004010001',
				'work_type_id' => 2,
				'date' => '2022-05-09',
				'description' => '欠勤',
			],
			[	// 有給休暇
				'user_id' => '202004010001',
				'work_type_id' => 6,
				'date' => '2022-05-10',
				'description' => '有給休暇',
			],
			[	// 有給休暇(バッチ用)
				'user_id' => '202004010001',
				'work_type_id' => 6,
				'date' => '2022-05-30',
				'description' => '有給休暇',
			],
			[	// 有給休暇(バッチ用)
				'user_id' => '202004010001',
				'work_type_id' => 6,
				'date' => '2022-05-31',
				'description' => '有給休暇',
			],
			[	// 特別休暇
				'user_id' => '202004010001',
				'work_type_id' => 7,
				'date' => '2022-05-11',
				'description' => '特別休暇',
			],
			[	// 退勤打刻忘れ
				'user_id' => '202004010001',
				'work_type_id' => 1,
				'date' => '2022-05-12',
				'start_time' => '09:30',
				'description' => '退勤打刻忘れ',
			],

			// 2022/05/30
			// [	// 発表時打刻
			// 	'user_id' => '202004010001',
			// 	'work_type_id' => 1,
			// 	'date' => '2022-05-30',
			// 	'start_time' => '09:25',
			// 	'left_time' => '18:05',
			// 	'rest_time' => '00:45',
			// 	'over_time' => '00:00',
			// ],
			[	// 通常出勤
				'user_id' => '202104010001',
				'work_type_id' => 1,
				'date' => '2022-05-30',
				'start_time' => '09:25',
				'left_time' => '18:05',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '通常出勤',
			],
			[	// 残業
				'user_id' => '202104010002',
				'work_type_id' => 1,
				'date' => '2022-05-30',
				'start_time' => '09:30',
				'left_time' => '19:00',
				'rest_time' => '01:00',
				'over_time' => '00:45',
				'description' => '残業',
			],
			[	// 遅刻
				'user_id' => '202204010001',
				'work_type_id' => 3,
				'date' => '2022-05-30',
				'start_time' => '10:00',
				'left_time' => '18:00',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '遅刻',
			],
			[	// 早退
				'user_id' => '202204010002',
				'work_type_id' => 4,
				'date' => '2022-05-30',
				'start_time' => '09:30',
				'left_time' => '16:00',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '早退',
			],
			[	// 遅刻/早退
				'user_id' => '202204010003',
				'work_type_id' => 5,
				'date' => '2022-05-30',
				'start_time' => '10:00',
				'left_time' => '15:00',
				'rest_time' => '00:00',
				'over_time' => '00:00',
				'description' => '遅刻かつ早退',
			],
			[	// 欠勤
				'user_id' => '202204010004',
				'work_type_id' => 2,
				'date' => '2022-05-30',
				'description' => '欠勤',
			],
			[	// 有給休暇
				'user_id' => '202204010005',
				'work_type_id' => 6,
				'date' => '2022-05-30',
				'description' => '有給休暇',
			],
			[	// 特別休暇
				'user_id' => '202204010006',
				'work_type_id' => 7,
				'date' => '2022-05-30',
				'description' => '特別休暇',
			],
				[	// 退勤打刻忘れ
				'user_id' => '202204010007',
				'work_type_id' => 1,
				'date' => '2022-05-30',
				'start_time' => '09:30',
				'description' => '退勤打刻忘れ',
			],
			// [	// 打刻忘れ
			// 	'user_id' => '202204010008',
			// 	'work_type_id' => 5,
			// 	'date' => '2022-05-30',
			// 	'start_time' => '10:00',
			// 	'left_time' => '15:00',
			// 	'rest_time' => '00:00',
			// 	'over_time' => '00:00',
			// ],

			// 2022/05/31
			// [	// 発表時打刻
			// 	'user_id' => '202004010001',
			// 	'work_type_id' => 1,
			// 	'date' => '2022-05-31',
			// 	'start_time' => '09:25',
			// 	'left_time' => '18:05',
			// 	'rest_time' => '00:45',
			// 	'over_time' => '00:00',
			// ],
			[	// 通常出勤
				'user_id' => '202104010001',
				'work_type_id' => 1,
				'date' => '2022-05-31',
				'start_time' => '09:25',
				'left_time' => '18:05',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '通常出勤',
			],
			[	// 残業
				'user_id' => '202104010002',
				'work_type_id' => 1,
				'date' => '2022-05-31',
				'start_time' => '09:30',
				'left_time' => '19:00',
				'rest_time' => '01:00',
				'over_time' => '00:45',
				'description' => '残業',
			],
			[	// 遅刻
				'user_id' => '202204010001',
				'work_type_id' => 3,
				'date' => '2022-05-31',
				'start_time' => '10:00',
				'left_time' => '18:00',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '遅刻',
			],
			[	// 早退
				'user_id' => '202204010002',
				'work_type_id' => 4,
				'date' => '2022-05-31',
				'start_time' => '09:30',
				'left_time' => '16:00',
				'rest_time' => '00:45',
				'over_time' => '00:00',
				'description' => '早退',
			],
			[	// 遅刻/早退
				'user_id' => '202204010003',
				'work_type_id' => 5,
				'date' => '2022-05-31',
				'start_time' => '10:00',
				'left_time' => '15:00',
				'rest_time' => '00:00',
				'over_time' => '00:00',
				'description' => '遅刻かつ早退',
			],
			[	// 欠勤
				'user_id' => '202204010004',
				'work_type_id' => 2,
				'date' => '2022-05-31',
				'description' => '欠勤',
			],
			[	// 有給休暇
				'user_id' => '202204010005',
				'work_type_id' => 6,
				'date' => '2022-05-31',
				'description' => '有給休暇',
			],
			[	// 特別休暇
				'user_id' => '202204010006',
				'work_type_id' => 7,
				'date' => '2022-05-31',
				'description' => '特別休暇',
			],
			[	// 退勤打刻忘れ
				'user_id' => '202204010007',
				'work_type_id' => 1,
				'date' => '2022-05-31',
				'start_time' => '09:30',
				'description' => '退勤打刻忘れ',
			],
			// [	// 打刻忘れ
			// 	'user_id' => '202204010008',
			// 	'work_type_id' => 5,
			// 	'date' => '2022-05-31',
			// 	'start_time' => '10:00',
			// 	'left_time' => '15:00',
			// 	'rest_time' => '00:00',
			// 	'over_time' => '00:00',
			// ],
        ];
		
        $now = Carbon::now();
        foreach ($params as $param) {
            $param['created_at'] = $now;
            $param['updated_at'] = $now;
            DB::table('work_times')->insert($param);
        }
    }
}
