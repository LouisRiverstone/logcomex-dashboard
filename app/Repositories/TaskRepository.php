<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\DTOs\Dashboard\TaskFilterDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Get the pending tasks
     *
     * @param int $limit
     * @return array<int,object>
     */
    public function getPendingTasks(int $limit = 4): array
    {
        return Task::select('id', 'title', 'due_date', 'priority', 'completed')
            ->orderBy('due_date')
            ->orderBy('priority')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Update the task
     *
     * @param int $taskId
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function updateTask(int $taskId, array $data): array
    {
        $task = Task::findOrFail($taskId);
        $task->update($data);
        return $task->toArray();
    }

    /**
     * Get the filtered tasks
     *
     * @param TaskFilterDTO $filter
     * @return array<int,object>|LengthAwarePaginator<Task>|non-empty-array<mixed>
     */
    public function getFilteredTasks(TaskFilterDTO $filter): array|LengthAwarePaginator
    {
        $query = Task::query();

        // Selecionar campos básicos
        $query->select('id', 'title', 'due_date', 'priority', 'completed', 'created_at', 'updated_at');

        // Adiciona campos opcionais se a tabela tiver esses campos
        if (in_array('assignee_id', $this->getTableColumns())) {
            $query->addSelect('assignee_id');
        }

        if (in_array('category_id', $this->getTableColumns())) {
            $query->addSelect('category_id');
        }

        if (in_array('description', $this->getTableColumns())) {
            $query->addSelect('description');
        }

        if (in_array('created_by', $this->getTableColumns())) {
            $query->addSelect('created_by');
        }

        // Filtros de prioridade
        if ($filter->priority) {
            $query->where('priority', $filter->priority);
        }

        if ($filter->priorities) {
            $query->whereIn('priority', $filter->priorities);
        }

        // Filtro de status de conclusão
        if ($filter->completed !== null) {
            $query->where('completed', $filter->completed);
        }

        // Filtros de data
        if ($filter->dueDate) {
            $query->whereDate('due_date', $filter->dueDate);
        }

        if ($filter->dueDateStart) {
            $query->where('due_date', '>=', $filter->dueDateStart);
        }

        if ($filter->dueDateEnd) {
            $query->where('due_date', '<=', $filter->dueDateEnd);
        }

        // Filtros de atribuição
        if ($filter->assignee && in_array('assignee_id', $this->getTableColumns())) {
            $query->where('assignee_id', $filter->assignee);
        }

        if ($filter->assignees && in_array('assignee_id', $this->getTableColumns())) {
            $query->whereIn('assignee_id', $filter->assignees);
        }

        // Filtros de categoria
        if ($filter->category && in_array('category_id', $this->getTableColumns())) {
            $query->where('category_id', $filter->category);
        }

        if ($filter->categories && in_array('category_id', $this->getTableColumns())) {
            $query->whereIn('category_id', $filter->categories);
        }

        // Filtro de criador
        if ($filter->createdBy && in_array('created_by', $this->getTableColumns())) {
            $query->where('created_by', $filter->createdBy);
        }

        // Busca por termo
        if ($filter->searchTerm) {
            $query->where(function ($q) use ($filter) {
                $q->where('title', 'like', '%' . $filter->searchTerm . '%');

                if (in_array('description', $this->getTableColumns())) {
                    $q->orWhere('description', 'like', '%' . $filter->searchTerm . '%');
                }
            });
        }

        // Agrupamento
        if ($filter->groupBy) {
            $groupResults = [];

            foreach ($filter->groupBy as $field) {
                if (in_array($field, $this->getTableColumns())) {
                    $groupQuery = clone $query;
                    $results = $groupQuery->select(
                        $field,
                        DB::raw('COUNT(*) as count'),
                        DB::raw('SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) as completed_count'),
                        DB::raw('SUM(CASE WHEN completed = 0 THEN 1 ELSE 0 END) as pending_count')
                    )
                    ->groupBy($field)
                    ->get()
                    ->toArray();

                    $groupResults[$field] = $results;
                }
            }

            if (!empty($groupResults)) {
                return $groupResults;
            }
        }

        // Ordenação
        if ($filter->sortBy) {
            $query->orderBy($filter->sortBy, $filter->sortDirection ?? 'asc');
        } else {
            $query->orderBy('due_date')->orderBy('priority');
        }

        // Paginação
        if ($filter->page && $filter->perPage) {
            return $query->paginate($filter->perPage, ['*'], 'page', $filter->page);
        }

        // Limite para consultas sem paginação
        if ($filter->limit) {
            $query->limit($filter->limit);
        }

        return $query->get()->toArray();
    }

    /**
     * Get the table columns
     *
     * @return array<string>
     */
    private function getTableColumns(): array
    {
        return DB::getSchemaBuilder()->getColumnListing('tasks');
    }
}
