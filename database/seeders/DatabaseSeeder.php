<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            CountrySeeder::class
        ]);

        // Do not allow seeders ('test data') to run in a production environment
        if (app()->environment() !== 'production') {
            Schema::disableForeignKeyConstraints();
            Schema::enableForeignKeyConstraints();
        }
    }
}
