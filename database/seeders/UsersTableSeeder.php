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
            [
                'id' => '202004010001',
                'department_id' => 1,
                'name' => '営業管理',
                'manager' => true,
                'email' => 'eigyoukannri@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2020-04-01',
            ],
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
                'id' => '202004010002',
                'department_id' => 2,
                'name' => '開発管理',
                'manager' => true,
                'email' => 'kaihatsukannri@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2020-04-01',
            ],
            [
                'id' => '202104010002',
                'department_id' => 2,
                'name' => '開発太郎',
                'manager' => false,
                'email' => 'kaihatsutarou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2021-04-01',
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
                'id' => '202104010003',
                'department_id' => 3,
                'name' => '経理太郎',
                'manager' => false,
                'email' => 'keiritarou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2021-04-01',
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
                'id' => '202104010004',
                'department_id' => 4,
                'name' => '管理太郎',
                'manager' => false,
                'email' => 'kannritarou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2021-04-01',
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
            [
                'id' => '202104010005',
                'department_id' => 5,
                'name' => '人事太郎',
                'manager' => false,
                'email' => 'jinnjitarou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2021-04-01',
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
