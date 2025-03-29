<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for tests.
     */
    public function run(): void
    {
        Artisan::call('migrate:fresh', ['--seed' => true, '--env' => 'testing']);
    }
}
