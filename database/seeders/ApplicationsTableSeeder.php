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
                'user_id' => '202204010001',
                'application_type_id' => 1,
                'date' => '2022-05-27',
                'status' => false,
                'reason' => '私用のため',
            ],
        ];

        DB::table('applications')->insert($params);
    }
}
