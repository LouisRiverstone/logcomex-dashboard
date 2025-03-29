<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(bool $isTestingEnv = false): void
    {
        $this->command->info('Criando vendas e itens de venda...');

        // Verificar se já existem vendas para habilitar continuação
        $existingCount = DB::table('sales')->count();
        $startFrom = $existingCount;

        $this->command->info("Verificando status: Encontradas {$existingCount} vendas existentes.");

        // Limitar a quantidade de vendas para economizar memória
        $totalSales = $isTestingEnv ? 25 : 2000;
        $batchSize = $isTestingEnv ? 10 : 200;

        // Se já terminou, apenas informar e sair
        if ($startFrom >= $totalSales) {
            $this->command->info("Todas as {$totalSales} vendas já foram criadas. Pulando este seeder.");
            return;
        }

        // Usar configurações mínimas do faker para economizar memória
        $faker = Faker::create();
        $faker->seed(1234); // Usar uma seed fixa para reduzir uso de memória

        // Criar apenas 20 produtos de referência em vez de carregar todos
        $productPrices = $this->getBasicProductPrices();

        // Status mais comuns com peso para otimizar
        $statusOptions = ['success', 'warning', 'danger'];
        $statusWeights = [80, 15, 5]; // porcentagens

        // Para cada lote pequeno
        for ($batchIndex = $startFrom; $batchIndex < $totalSales; $batchIndex += $batchSize) {
            $endIndex = min($batchIndex + $batchSize, $totalSales);

            $this->command->info("Processando vendas " . ($batchIndex + 1) . " até " . $endIndex . " de " . $totalSales);

            // Processar cada venda individualmente sem transações grandes
            for ($i = $batchIndex; $i < $endIndex; $i++) {
                try {
                    // Criar uma transação por venda
                    DB::beginTransaction();

                    // Dados simplificados para economia de memória
                    $saleDate = date('Y-m-d H:i:s', mt_rand(strtotime('-2 years'), time()));

                    // Smaller user ID range in testing environment
                    $maxUserId = $isTestingEnv ? 50 : 1000;
                    $userId = mt_rand(1, $maxUserId);

                    // Determinar status baseado em pesos
                    $statusRand = mt_rand(1, 100);
                    $cumulative = 0;
                    $status = $statusOptions[0]; // Default

                    for ($s = 0; $s < count($statusOptions); $s++) {
                        $cumulative += $statusWeights[$s];
                        if ($statusRand <= $cumulative) {
                            $status = $statusOptions[$s];
                            break;
                        }
                    }

                    // Criar venda simples
                    $saleId = DB::table('sales')->insertGetId([
                        'user_id' => $userId,
                        'amount' => 0, // Será atualizado depois
                        'status' => $status,
                        'created_at' => $saleDate,
                        'updated_at' => $saleDate,
                    ]);

                    // Apenas 1-5 itens por venda para economizar memória
                    // For testing, use 1-3 items
                    $maxItems = $isTestingEnv ? 3 : 5;
                    $itemCount = mt_rand(1, $maxItems);
                    $totalAmount = 0;

                    // Inserir cada item individualmente
                    for ($j = 0; $j < $itemCount; $j++) {
                        $productId = array_rand($productPrices);
                        $quantity = mt_rand(1, 3);
                        $price = $productPrices[$productId];

                        DB::table('sale_items')->insert([
                            'sale_id' => $saleId,
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'price' => $price,
                            'created_at' => $saleDate,
                            'updated_at' => $saleDate,
                        ]);

                        $totalAmount += $price * $quantity;
                    }

                    // Atualizar total da venda
                    DB::table('sales')
                        ->where('id', $saleId)
                        ->update(['amount' => $totalAmount]);

                    // Criar transação relacionada
                    DB::table('transactions')->insert([
                        'user_id' => $userId,
                        'type' => 'sale',
                        'amount' => $totalAmount,
                        'status' => $status,
                        'description' => 'Venda #' . $saleId,
                        'created_at' => $saleDate,
                        'updated_at' => $saleDate,
                    ]);

                    // Finalizar transação
                    DB::commit();

                } catch (\Exception $e) {
                    // Rollback em caso de erro
                    if (DB::transactionLevel() > 0) {
                        DB::rollBack();
                    }

                    $this->command->error("Erro na venda #{$i}: " . $e->getMessage() . " - Continuando com a próxima.");

                    // Pausa para recuperação
                    sleep(1);
                }

                // Forçar liberação de memória a cada venda
                if (!$isTestingEnv && $i % 2 == 0) {
                    gc_collect_cycles();
                }
            }

            // Checkpoint a cada lote pequeno
            $this->command->info("Checkpoint: {$endIndex} vendas processadas.");

            // Pausa significativa entre lotes para permitir recuperação do sistema - skip in testing
            if (!$isTestingEnv) {
                sleep(1);
                gc_collect_cycles();
            }
        }

        $this->command->info('Criação de vendas concluída com sucesso!');
    }

    /**
     * Retorna um conjunto básico de preços de produtos para economia de memória
     */
    private function getBasicProductPrices()
    {
        // Usar apenas alguns produtos para reduzir uso de memória
        return [
            1 => 1299.99,  // Smartphone
            2 => 4599.99,  // Notebook
            3 => 299.99,   // Fone de Ouvido
            4 => 2499.99,  // Smart TV
            5 => 79.99,    // Camiseta
            6 => 149.99,   // Calça Jeans
            7 => 199.99,   // Vestido
            8 => 29.99,    // Café
            9 => 12.99,    // Chocolate
            10 => 22.99,   // Arroz
            11 => 49.99,   // Romance
            12 => 59.99,   // Ficção
            13 => 39.99,   // Autoajuda
            14 => 1499.99, // Sofá
            15 => 799.99,  // Mesa
            16 => 299.99,  // Cadeira
            17 => 89.99,   // Bola
            18 => 129.99,  // Raquete
            19 => 249.99,  // Tênis
            20 => 59.99,   // Maquiagem
        ];
    }
}
