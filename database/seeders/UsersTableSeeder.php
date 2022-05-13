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
                'id' => '202204010001',
                'department_id' => 1,
                'name' => '営業太郎',
                'manager' => false,
                'email' => 'eigyoutarou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204010002',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujirou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204011113',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujiro@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204011114',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujir@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204011115',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyouj@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204010006',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204010007',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyo@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204010008',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'ei@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204010009',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigy@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204010012',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'e@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204010102',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujiroua@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204011002',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujirous@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202204110002',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujiroud@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202214010002',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujirouf@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202214110002',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujiroug@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '202211110002',
                'department_id' => 1,
                'name' => '営業次郎',
                'manager' => false,
                'email' => 'eigyoujirouh@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            
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
                'id' => '202204010003',
                'department_id' => 2,
                'name' => '開発太郎',
                'manager' => false,
                'email' => 'kaihatutarou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '201904010003',
                'department_id' => 2,
                'name' => '開発管理',
                'manager' => true,
                'email' => 'kaihatukannri@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2019-04-01',
            ],

            [
                'id' => '202204010004',
                'department_id' => 3,
                'name' => '経理太郎',
                'manager' => false,
                'email' => 'keiritarou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            
            [
                'id' => '202204010005',
                'department_id' => 5,
                'name' => '人事太郎',
                'manager' => false,
                'email' => 'jinnjitarou@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2022-04-01',
            ],
            [
                'id' => '201704010001',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannriaaaaa@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010002',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannria@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010003',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannris@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010004',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannrid@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010005',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannrif@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010006',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannrig@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010007',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannrih@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010008',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannriz@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010009',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannrix@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010010',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannric@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010011',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannrvi@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010012',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannriv@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010013',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannri@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010014',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannfri@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010015',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikangnri@test.com',
                'password' => Hash::make('hogehoge'),
                'joining' => '2017-04-01',
            ],
            [
                'id' => '201704010016',
                'department_id' => 5,
                'name' => '人事管理',
                'manager' => true,
                'email' => 'jinnjikannjri@test.com',
                'password' => Hash::make('hogehoge'),
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
