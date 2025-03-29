<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(bool $isTestingEnv = false): void
    {
        $faker = Faker::create('pt_BR');

        // Regiões
        $regions = ['Brasil', 'EUA', 'Europa', 'Ásia', 'Outros'];

        // Pré-gerar senha para evitar hashing repetido
        $hashedPassword = Hash::make('password');

        // Determine the number of users based on environment
        $totalUsers = $isTestingEnv ? 50 : 10000;

        $this->command->info("Criando {$totalUsers} usuários...");

        // Desativar verificação de chaves estrangeiras temporariamente para melhorar performance
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $batchSize = $isTestingEnv ? 25 : 1500;

        try {
            for ($batch = 0; $batch < ceil($totalUsers / $batchSize); $batch++) {
                $this->command->info("Processando lote " . ($batch + 1) . " de " . ceil($totalUsers / $batchSize));

                $startIndex = $batch * $batchSize;
                $endIndex = min(($batch + 1) * $batchSize, $totalUsers);

                // Processar em mini-lotes de 100 para reduzir uso de memória
                // Use smaller mini-batches for testing
                $miniBatchSize = $isTestingEnv ? 10 : 100;

                for ($miniBatch = $startIndex; $miniBatch < $endIndex; $miniBatch += $miniBatchSize) {
                    $miniEndIndex = min($miniBatch + $miniBatchSize, $endIndex);

                    DB::beginTransaction();
                    $users = [];

                    for ($i = $miniBatch; $i < $miniEndIndex; $i++) {
                        $lastActive = $faker->dateTimeBetween('-3 years', 'now');
                        $createdAt = $faker->dateTimeBetween('-4 years', $lastActive);
                        $updatedAt = $faker->dateTimeBetween($createdAt, $lastActive);

                        // Email único sem usar o faker unique()
                        $email = 'user' . $i . '_' . substr(md5(microtime()), 0, 8) . '@email.com';

                        $users[] = [
                            'name' => $faker->firstName . ' ' . $faker->lastName,
                            'email' => $email,
                            'password' => $hashedPassword,
                            'region' => $regions[array_rand($regions)],
                            'last_active_at' => $lastActive,
                            'email_verified_at' => now(),
                            'created_at' => $createdAt,
                            'updated_at' => $updatedAt,
                        ];
                    }

                    // Inserir mini-lote de usuários
                    DB::table('users')->insert($users);
                    DB::commit();

                    // Liberar memória
                    $users = null;
                    unset($users);
                    gc_collect_cycles();

                    // Pequena pausa para liberar recursos - Only for non-test environments
                    if (!$isTestingEnv && $miniBatch > $startIndex) {
                        usleep(100000); // 0.1 segundos
                    }
                }

                // Relatório de progresso
                $this->command->info("Criados " . $endIndex . " de " . $totalUsers . " usuários");

                // Pausa maior entre lotes grandes - Only for non-test environments
                if (!$isTestingEnv && $batch < ceil($totalUsers / $batchSize) - 1) {
                    $this->command->info("Pausa para liberação de recursos...");
                    sleep(1);
                    gc_collect_cycles();
                }
            }

            $this->command->info("{$totalUsers} usuários criados com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Erro ao criar usuários: ' . $e->getMessage());
            throw $e;
        } finally {
            // Reativar verificação de chaves estrangeiras
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
