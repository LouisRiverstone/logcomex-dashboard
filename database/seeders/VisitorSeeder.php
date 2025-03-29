<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;
use Faker\Factory as Faker;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        $this->command->info('Criando visitantes...');
        // Fontes de tráfego
        $sources = ['Busca Orgânica', 'Redes Sociais', 'Email', 'Direto', 'Referência'];

        // Criar dados de visitantes em lotes
        $visitors = [];
        for ($i = 0; $i < 5000; $i++) {
            $visitDate = $faker->dateTimeBetween('-1 year', 'now');

            $visitors[] = [
                'ip' => $faker->ipv4,
                'source' => $faker->randomElement($sources),
                'country' => $faker->country,
                'visited_at' => $visitDate,
                'created_at' => $visitDate,
                'updated_at' => $visitDate,
            ];

            if (count($visitors) === 500 || $i === 4999) {
                Visitor::insert($visitors);
                $visitors = [];
            }
        }
    }
}
