<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
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
			// 2020年入社（主に管理者）
			[
				'id' => '202004010001',
				'department_id' => 1,
				'name' => '営業管理',
				'manager' => true,
				'email' => 'eigyoukannri@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-11-30',
			],
			[
				'id' => '202004010002',
				'department_id' => 2,
				'name' => '開発管理',
				'manager' => true,
				'email' => 'kaihatsukannri@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2020-04-01',
			],
			[
				'id' => '202004010003',
				'department_id' => 3,
				'name' => '経理管理',
				'manager' => true,
				'email' => 'keirikannri@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2020-04-01',
			],
			[
				'id' => '202004010004',
				'department_id' => 4,
				'name' => '管理管理',
				'manager' => true,
				'email' => 'kannrikannri@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2020-04-01',
			],
			[
				'id' => '202004010005',
				'department_id' => 5,
				'name' => '人事管理',
				'manager' => true,
				'email' => 'jinnjikannri@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2020-04-01',
			],

			// 2021年入社
			[
				'id' => '202104010001',
				'department_id' => 1,
				'name' => '営業太郎',
				'manager' => false,
				'email' => 'eigyoutarou@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
			[
				'id' => '202104010002',
				'department_id' => 1,
				'name' => '営業花子',
				'manager' => false,
				'email' => 'eigyouhanako@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
			[
				'id' => '202104010003',
				'department_id' => 2,
				'name' => '開発太郎',
				'manager' => false,
				'email' => 'kaihatsutarou@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
			[
				'id' => '202104010004',
				'department_id' => 2,
				'name' => '開発花子',
				'manager' => false,
				'email' => 'kaihatsuhanako@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
			[
				'id' => '202104010005',
				'department_id' => 3,
				'name' => '経理太郎',
				'manager' => false,
				'email' => 'keiritarou@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
			[
				'id' => '202104010006',
				'department_id' => 3,
				'name' => '経理花子',
				'manager' => false,
				'email' => 'keirihanako@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
			[
				'id' => '202104010007',
				'department_id' => 4,
				'name' => '管理太郎',
				'manager' => false,
				'email' => 'kannritarou@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
			[
				'id' => '202104010008',
				'department_id' => 4,
				'name' => '管理花子',
				'manager' => false,
				'email' => 'kannrihanako@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
			[
				'id' => '202104010009',
				'department_id' => 5,
				'name' => '人事太郎',
				'manager' => false,
				'email' => 'jinnjitarou@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
			[
				'id' => '202104010010',
				'department_id' => 5,
				'name' => '人事花子',
				'manager' => false,
				'email' => 'jinnjihanako@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2021-04-01',
			],
            //バッチ処理用
			// [
			// 	'id' => '202111300011',
			// 	'department_id' => 5,
			// 	'name' => '人事一郎',
			// 	'manager' => false,
			// 	'email' => 'jinnjiichirou@test.com',
			// 	'password' => Hash::make('hogehoge'),
			// 	'joining' => '2021-11-30',
			// ],

			// 2022年入社
			[
				'id' => '202204010001',
				'department_id' => 1,
				'name' => '営業次郎',
				'manager' => false,
				'email' => 'eigyoujirou@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2022-04-01',
			],
			[
				'id' => '202204010002',
				'department_id' => 1,
				'name' => '営業三郎',
				'manager' => false,
				'email' => 'eigyousaburou@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2022-04-01',
			],
			[
				'id' => '202204010003',
				'department_id' => 1,
				'name' => '営業史郎',
				'manager' => false,
				'email' => 'eigyoushirou@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2022-04-01',
			],
			[
				'id' => '202204010004',
				'department_id' => 1,
				'name' => '営業吾郎',
				'manager' => false,
				'email' => 'eigyougorou@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2022-04-01',
			],
			[
				'id' => '202204010005',
				'department_id' => 1,
				'name' => '営業春子',
				'manager' => false,
				'email' => 'eigyouharuko@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2022-04-01',
			],
			[
				'id' => '202204010006',
				'department_id' => 1,
				'name' => '営業夏子',
				'manager' => false,
				'email' => 'eigyounatsuko@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2022-04-01',
			],
			[
				'id' => '202204010007',
				'department_id' => 1,
				'name' => '営業秋子',
				'manager' => false,
				'email' => 'eigyouakiko@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2022-04-01',
			],
			[
				'id' => '202204010008',
				'department_id' => 1,
				'name' => '営業冬子',
				'manager' => false,
				'email' => 'eigyoufuyuko@test.com',
				'password' => Hash::make('hogehoge'),
				'joining' => '2022-04-01',
			],
        ];

        $now = Carbon::now();
        foreach ($params as $param) {
            $param['created_at'] = $now;
            $param['updated_at'] = $now;
            DB::table('users')->insert($param);
        }
    }
}
