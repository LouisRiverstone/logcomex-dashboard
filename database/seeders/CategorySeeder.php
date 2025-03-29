<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Criando categorias...');

        // Criar categorias
        $categories = [
            'Eletrônicos',
            'Roupas',
            'Alimentos',
            'Livros',
            'Casa',
            'Esportes',
            'Beleza',
            'Saúde',
            'Brinquedos',
            'Ferramentas',
        ];

        $categoryData = [];
        foreach ($categories as $category) {
            $categoryData[] = [
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        Category::insert($categoryData);
    }
}
