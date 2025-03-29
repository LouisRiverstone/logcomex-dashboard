<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class RevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(bool $isTestingEnv = false): void
    {
        $faker = Faker::create('pt_BR');

        $this->command->info('Criando receitas...');
        // Criar dados de receita em lotes menores
        $types = ['service', 'product', 'subscription'];

        // Use smaller date range for testing
        $startDate = $isTestingEnv
            ? Carbon::now()->subMonths(3)
            : Carbon::now()->subYears(1);

        $endDate = Carbon::now();

        // Significantly reduce data size for testing
        $totalRevenues = $isTestingEnv ? 100 : 10000;
        $counter = 0;

        // Criar lotes de 30 dias por vez
        $currentDate = clone $startDate;
        $batchEndDate = clone $currentDate;
        $batchEndDate->addDays($isTestingEnv ? 15 : 30);

        while ($currentDate->lte($endDate) && $counter < $totalRevenues) {
            $this->command->info("Processando receitas para período: " . $currentDate->format('Y-m-d') . " até " . min($batchEndDate, $endDate)->format('Y-m-d'));

            $processingDate = clone $currentDate;

            while ($processingDate->lte(min($batchEndDate, $endDate)) && $counter < $totalRevenues) {
                DB::beginTransaction();
                try {
                    $revenues = [];

                    // Gerar apenas 15 receitas por dia (5 de cada tipo)
                    // For testing, generate just 3 per day (1 of each type)
                    $revenuesPerType = $isTestingEnv ? 1 : 5;

                    for ($i = 0; $i < $revenuesPerType && $counter < $totalRevenues; $i++) {
                        foreach ($types as $type) {
                            $amount = $faker->randomFloat(2, 5000, 20000);

                            $revenues[] = [
                                'type' => $type,
                                'amount' => $amount,
                                'date' => $processingDate->format('Y-m-d'),
                                'created_at' => $processingDate,
                                'updated_at' => $processingDate,
                            ];

                            $counter++;
                            if ($counter >= $totalRevenues) {
                                break;
                            }
                        }
                    }

                    // Inserir receitas do dia atual
                    DB::table('revenues')->insert($revenues);
                    DB::commit();

                    // Liberar memória
                    $revenues = null;
                    unset($revenues);

                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->command->error("Erro ao criar receitas: " . $e->getMessage());
                    throw $e;
                }

                $processingDate->addDay();

                // Forçar coleta de lixo a cada dia - only when not testing
                if (!$isTestingEnv) {
                    gc_collect_cycles();
                }
            }

            $currentDate = clone $batchEndDate;
            $batchEndDate->addDays($isTestingEnv ? 15 : 30);

            // Pequena pausa entre períodos - only when not testing
            if (!$isTestingEnv && $counter < $totalRevenues) {
                usleep(500000); // 0.5 segundos
            }
        }

        $this->command->info("{$counter} receitas criadas com sucesso!");
    }
}
