<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Determine if we're in testing environment
        $isTestingEnv = App::environment('testing');

        // Desativar eventos do Eloquent para melhorar a performance
        Event::fake();

        // Desativar foreign key checks durante o seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $message = $isTestingEnv
            ? 'Iniciando seeding com dataset reduzido para testes...'
            : 'Iniciando seeding - Isso pode levar algum tempo...';

        $this->command->info($message);

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductRatingSeeder::class,
            VisitorSeeder::class,
            RevenueSeeder::class,
            TaskSeeder::class,
            SaleSeeder::class,
            TransactionSeeder::class,
        ], $isTestingEnv);

        // Reativar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $successMessage = $isTestingEnv
            ? 'Processo de seeding com dataset reduzido finalizado com sucesso!'
            : 'Processo de seeding finalizado com sucesso!';

        $this->command->info($successMessage);
    }
}
