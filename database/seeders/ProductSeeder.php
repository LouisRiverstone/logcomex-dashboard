<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        $this->command->info('Criando produtos...');
        // Criar produtos em lotes
        $productTemplates = [
            ['name' => 'Smartphone', 'category_id' => 1, 'price' => 1299.99],
            ['name' => 'Notebook', 'category_id' => 1, 'price' => 4599.99],
            ['name' => 'Fone de Ouvido', 'category_id' => 1, 'price' => 299.99],
            ['name' => 'Smart TV', 'category_id' => 1, 'price' => 2499.99],
            ['name' => 'Camiseta', 'category_id' => 2, 'price' => 79.99],
            ['name' => 'Calça Jeans', 'category_id' => 2, 'price' => 149.99],
            ['name' => 'Vestido', 'category_id' => 2, 'price' => 199.99],
            ['name' => 'Café', 'category_id' => 3, 'price' => 29.99],
            ['name' => 'Chocolate', 'category_id' => 3, 'price' => 12.99],
            ['name' => 'Arroz', 'category_id' => 3, 'price' => 22.99],
            ['name' => 'Romance', 'category_id' => 4, 'price' => 49.99],
            ['name' => 'Ficção', 'category_id' => 4, 'price' => 59.99],
            ['name' => 'Autoajuda', 'category_id' => 4, 'price' => 39.99],
            ['name' => 'Sofá', 'category_id' => 5, 'price' => 1499.99],
            ['name' => 'Mesa', 'category_id' => 5, 'price' => 799.99],
            ['name' => 'Cadeira', 'category_id' => 5, 'price' => 299.99],
            ['name' => 'Bola', 'category_id' => 6, 'price' => 89.99],
            ['name' => 'Raquete', 'category_id' => 6, 'price' => 129.99],
            ['name' => 'Tênis', 'category_id' => 6, 'price' => 249.99],
            ['name' => 'Maquiagem', 'category_id' => 7, 'price' => 59.99],
        ];

        $products = [];
        for ($i = 0; $i < 2000; $i++) {
            $template = $productTemplates[$i % count($productTemplates)];
            $variationIndex = floor($i / count($productTemplates)) + 1;

            $products[] = [
                'name' => $template['name'] . " " . $variationIndex,
                'category_id' => $template['category_id'],
                'price' => $template['price'] * (0.9 + (mt_rand(0, 20) / 100)),
                'description' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($products) === 500 || $i === 1999) {
                Product::insert($products);
                $products = [];
            }
        }
    }
}
