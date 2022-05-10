<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApplicationTypesTableSeeder extends Seeder
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
                'name' => '有給休暇',
            ],
            [
                'name' => '特別休暇',
            ],
            [
                'name' => '休日出勤',
            ],
            [
                'name' => '時間外勤務',
            ],
            [
                'name' => '打刻時間修正',
            ],
        ];

        $now = Carbon::now();
        foreach ($params as $param) {
            $param['created_at'] = $now;
            $param['updated_at'] = $now;
            DB::table('application_types')->insert($param);
        }
    }
}
