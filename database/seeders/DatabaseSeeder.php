<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                DepartmentsTableSeeder::class,
                UsersTableSeeder::class,
                ApplicationTypesTableSeeder::class,
                WorkTypesTableSeeder::class,
                AddPaidLeavesTableSeeder::class,
                ApplicationsTableSeeder::class,
                FixedTimesTableSeeder::class,
                PaidLeavesTableSeeder::class,
                WorkTimesTableSeeder::class,
            ]
        );
    }
}
