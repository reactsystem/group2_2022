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
                ApplicationTypesTableSeeder::class,
                DepartmentsTableSeeder::class,
                UsersTableSeeder::class,
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
