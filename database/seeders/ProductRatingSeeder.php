<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        $this->command->info('Criando avaliações de produtos...');

        // Reduzir tamanho do lote para 100 avaliações por vez
        $totalRatings = 2000;
        $batchSize = 100;

        for ($batchIndex = 0; $batchIndex < $totalRatings; $batchIndex += $batchSize) {
            $endIndex = min($batchIndex + $batchSize, $totalRatings);
            $this->command->info("Processando avaliações " . ($batchIndex + 1) . " até " . $endIndex . " de " . $totalRatings);

            $productRatings = [];
            DB::beginTransaction();

            try {
                for ($i = $batchIndex; $i < $endIndex; $i++) {
                    $productRatings[] = [
                        'product_id' => $faker->numberBetween(1, 2000),
                        'user_id' => $faker->numberBetween(1, 10000),
                        'quality' => $faker->randomFloat(1, 5, 10),
                        'price' => $faker->randomFloat(1, 5, 10),
                        'usability' => $faker->randomFloat(1, 5, 10),
                        'design' => $faker->randomFloat(1, 5, 10),
                        'support' => $faker->randomFloat(1, 5, 10),
                        'features' => $faker->randomFloat(1, 5, 10),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Inserir a cada 25 registros para economizar memória
                    if (count($productRatings) === 25 || $i === $endIndex - 1) {
                        DB::table('product_ratings')->insert($productRatings);
                        $productRatings = [];
                    }
                }

                DB::commit();

                // Liberar memória
                $productRatings = null;
                unset($productRatings);
                gc_collect_cycles();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("Erro ao criar avaliações: " . $e->getMessage());
                throw $e;
            }

            // Pequena pausa a cada 500 registros
            if ($batchIndex % 500 == 0 && $batchIndex > 0) {
                usleep(500000); // 0.5 segundos
            }
        }

        $this->command->info('2.000 avaliações de produtos criadas com sucesso!');
    }
}
