<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\TaskFilterDTO;
use App\DTOs\Dashboard\Output\TasksOutputDTO;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\TaskRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class GetTasksUseCase
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(?TaskRepositoryInterface $taskRepository = null)
    {
        $this->taskRepository = $taskRepository ?? new TaskRepository();
    }

    public function execute(?TaskFilterDTO $filter): TasksOutputDTO
    {
        $tasks = $filter
            ? $this->taskRepository->getFilteredTasks($filter)
            : $this->taskRepository->getPendingTasks();

        // Verificar se temos um resultado de agrupamento
        if (is_array($tasks) && (isset($tasks['priority']) || isset($tasks['assignee_id']) || isset($tasks['category_id']))) {
            // Create a new associative array with string keys
            $tasksOutput = [];
            
            // Convert all numeric keys to string keys
            foreach ($tasks as $key => $value) {
                if (is_array($value)) {
                    // If value is an array, we need to process it too
                    $processedValue = [];
                    foreach ($value as $subKey => $subValue) {
                        $processedValue[(string)$subKey] = $subValue;
                    }
                    $tasksOutput[(string)$key] = $processedValue;
                } else {
                    $tasksOutput[(string)$key] = $value;
                }
            }
            
            // Retornar os dados de agrupamento diretamente
            /** @phpstan-ignore-next-line */
            return new TasksOutputDTO($tasksOutput);
        }

        // Verificar se é um objeto de paginação
        if ($tasks instanceof LengthAwarePaginator) {
            $paginationData = [
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'last_page' => $tasks->lastPage(),
            ];

            $taskItems = $tasks->items();

            $formattedTasks = $this->formatTasks($taskItems);

            return new TasksOutputDTO([
                'data' => $formattedTasks,
                'pagination' => $paginationData
            ]);
        }

        // Tratamento padrão para array de tarefas
        $formattedTasks = $this->formatTasks($tasks);

        return new TasksOutputDTO([
            'data' => $formattedTasks
        ]);
    }

    /**
     * Format tasks array
     * 
     * @param array<int,mixed> $tasks
     * @return array<int,array<string,mixed>>
     */
    private function formatTasks(array $tasks): array
    {
        return collect($tasks)->map(function ($task) {
            // Verificar se task['due_date'] existe antes de parsear
            if (!isset($task['due_date'])) {
                return [
                    'id' => $task['id'] ?? null,
                    'title' => $task['title'] ?? 'Sem título',
                    'dueDate' => 'Não definida',
                    'priority' => $task['priority'] ?? 'normal',
                    'completed' => (bool) ($task['completed'] ?? false)
                ];
            }

            $dueDate = Carbon::parse($task['due_date']);

            if ($dueDate->isToday()) {
                $formattedDate = 'Hoje, ' . $dueDate->format('H:i');
            } elseif ($dueDate->isTomorrow()) {
                $formattedDate = 'Amanhã, ' . $dueDate->format('H:i');
            } else {
                $formattedDate = $dueDate->format('d M');
            }

            return [
                'id' => $task['id'] ?? null,
                'title' => $task['title'] ?? 'Sem título',
                'dueDate' => $formattedDate,
                'priority' => $task['priority'] ?? 'normal',
                'completed' => (bool) ($task['completed'] ?? false)
            ];
        })->toArray();
    }
}
