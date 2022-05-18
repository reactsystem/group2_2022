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
                'joining' => '2000-04-01',
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

        ];

        $now = Carbon::now();
        foreach ($params as $param) {
            $param['created_at'] = $now;
            $param['updated_at'] = $now;
            DB::table('users')->insert($param);
        }
    }
}
