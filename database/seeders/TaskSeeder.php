<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        $this->command->info('Criando tarefas...');
        // Criar tarefas em lotes
        $priorities = ['danger', 'warning', 'info', 'success'];

        $tasks = [];

        for ($i = 0; $i < 2000; $i++) {
            $dueDate = $faker->dateTimeBetween('-5 days', '+30 days');
            $completed = $dueDate < now() ? $faker->boolean(70) : $faker->boolean(30);

            $tasks[] = [
                'title' => $faker->sentence(6),
                'due_date' => $dueDate,
                'priority' => $faker->randomElement($priorities),
                'completed' => $completed,
                'created_at' => $faker->dateTimeBetween('-60 days', '-1 day'),
                'updated_at' => now(),
            ];

            if (count($tasks) === 500 || $i === 1999) {
                Task::insert($tasks);
                $tasks = [];
            }
        }
    }
}
