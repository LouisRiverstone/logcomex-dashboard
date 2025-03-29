<?php

namespace App\Repositories\Interfaces;

use App\DTOs\Dashboard\TaskFilterDTO;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    /**
     * Get the pending tasks
     *
     * @param int $limit
     * @return array<int,object>
     */
    public function getPendingTasks(int $limit = 4): array;

    /**
     * Update the task
     *
     * @param int $taskId
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function updateTask(int $taskId, array $data): array;

    /**
     * Get the filtered tasks
     *
     * @param TaskFilterDTO $filter
     * @return array<int,object>|LengthAwarePaginator<object>
     */
    public function getFilteredTasks(TaskFilterDTO $filter): array|LengthAwarePaginator;
}
