<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                'id' => '202204010001',
                'department_id' => 1,
                'name' => '営業太郎',
                'manager' => false,
                'email' => 'eigyoutarou@test.com',
                'password' => 'test',
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204010002',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujirou@test.com',
                'password' => 'test',
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202004010001',
                'department_id' => 1,
                'name' => '営業管理',
                'manager' => true,
                'email' => 'eigyoukannri@test.com',
                'password' => 'test',
                'joining' => '2020-04-01',
            ],


            [
                'id' => '202204010003',
                'department_id' => 2,
                'name' => '開発太郎',
                'manager' => false,
                'email' => 'kaihatutarou@test.com',
                'password' => 'test',
                'joining' => '2022-04-01',
            ],
            [
                'id' => '201904010003',
                'department_id' => 2,
                'name' => '開発管理',
                'manager' => true,
                'email' => 'kaihatukannri@test.com',
                'password' => 'test',
                'joining' => '2019-04-01',
            ],

            [
                'id' => '202204010004',
                'department_id' => 3,
                'name' => '経理太郎',
                'manager' => false,
                'email' => 'keiritarou@test.com',
                'password' => 'test',
                'joining' => '2022-04-01',
            ],
            
            [
                'id' => '202204010005',
                'department_id' => 5,
                'name' => '人事太郎',
                'manager' => false,
                'email' => 'jinnjitarou@test.com',
                'password' => 'test',
                'joining' => '2022-04-01',
            ],
            [
                'id' => '201704010001',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannri@test.com',
                'password' => 'test',
                'joining' => '2017-04-01',
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