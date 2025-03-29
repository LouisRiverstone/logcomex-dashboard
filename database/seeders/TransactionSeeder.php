<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        $this->command->info('Criando reembolsos...');

        $totalRefunds = 2000;
        $batchSize = 100;

        for ($batchIndex = 0; $batchIndex < $totalRefunds; $batchIndex += $batchSize) {
            $endIndex = min($batchIndex + $batchSize, $totalRefunds);
            $this->command->info("Processando reembolsos " . ($batchIndex + 1) . " até " . $endIndex . " de " . $totalRefunds);

            $refunds = [];
            DB::beginTransaction();

            try {
                for ($i = $batchIndex; $i < $endIndex; $i++) {
                    $refundDate = $faker->dateTimeBetween('-1 year', 'now');
                    $userId = $faker->numberBetween(1, 10000);
                    $amount = $faker->randomFloat(2, 50, 500);

                    $refunds[] = [
                        'user_id' => $userId,
                        'type' => 'refund',
                        'amount' => $amount,
                        'status' => 'warning',
                        'description' => 'Reembolso #' . $faker->numberBetween(500, 20000),
                        'created_at' => $refundDate,
                        'updated_at' => $refundDate,
                    ];

                    // Inserir a cada 10 registros para economizar memória
                    if (count($refunds) >= 10 || $i == $endIndex - 1) {
                        DB::table('transactions')->insert($refunds);
                        $refunds = [];
                    }
                }

                DB::commit();

                // Liberar memória
                $refunds = null;
                unset($refunds);
                gc_collect_cycles();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("Erro ao criar reembolsos: " . $e->getMessage());
                throw $e;
            }

            // Pausa para liberar recursos a cada 200 registros
            if ($batchIndex % 100 == 0 && $batchIndex > 0) {
                sleep(1);
            }
        }

        $this->command->info('Criando pagamentos...');
        // Reduzir para lotes de 50 pagamentos
        $totalPayments = 2000;
        $batchSize = 100;

        for ($batchIndex = 0; $batchIndex < $totalPayments; $batchIndex += $batchSize) {
            $endIndex = min($batchIndex + $batchSize, $totalPayments);
            $this->command->info("Processando pagamentos " . ($batchIndex + 1) . " até " . $endIndex . " de " . $totalPayments);

            $payments = [];
            DB::beginTransaction();

            try {
                for ($i = $batchIndex; $i < $endIndex; $i++) {
                    $paymentDate = $faker->dateTimeBetween('-1 year', 'now');
                    $userId = $faker->numberBetween(1, 10000);
                    $amount = $faker->randomFloat(2, 500, 5000);

                    $payments[] = [
                        'user_id' => $userId,
                        'type' => 'payment',
                        'amount' => $amount,
                        'status' => 'danger',
                        'description' => 'Pagamento #' . $faker->numberBetween(5000, 50000),
                        'created_at' => $paymentDate,
                        'updated_at' => $paymentDate,
                    ];

                    // Inserir a cada 10 registros para economizar memória
                    if (count($payments) >= 10 || $i == $endIndex - 1) {
                        DB::table('transactions')->insert($payments);
                        $payments = [];
                    }
                }

                DB::commit();

                // Liberar memória
                $payments = null;
                unset($payments);
                gc_collect_cycles();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("Erro ao criar pagamentos: " . $e->getMessage());
                throw $e;
            }

            // Pausa para liberar recursos a cada 100 registros
            if ($batchIndex % 100 == 0 && $batchIndex > 0) {
                sleep(1);
            }
        }

        $this->command->info('Criação de transações adicionais concluída com sucesso!');
    }
}
