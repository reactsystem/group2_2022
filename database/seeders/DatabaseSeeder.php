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
                AddPaidLeavesTableSeeder::class,
                ApplicationsTableSeeder::class,
                ApplicationTypesTableSeeder::class,
                DepartmentsTableSeeder::class,
                FixedTimesTableSeeder::class,
                PaidLeavesTableSeeder::class,
                UsersTableSeeder::class,
                WorkTimesTableSeeder::class,
                WorkTypesTableSeeder::class,
            ]
        );
    }
}
