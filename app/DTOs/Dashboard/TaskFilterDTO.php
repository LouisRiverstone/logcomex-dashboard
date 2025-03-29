<?php

namespace App\DTOs\Dashboard;

use Carbon\Carbon;

class TaskFilterDTO extends BaseFilterDTO
{
    /**
     * Create a new TaskFilterDTO
     *
     * @param null|string $priority
     * @param null|bool $completed
     * @param null|Carbon $dueDate
     * @param null|Carbon $dueDateStart
     * @param null|Carbon $dueDateEnd
     * @param null|array<string,mixed> $priorities
     * @param null|string $assignee
     * @param null|array<string,mixed> $assignees
     * @param null|string $category
     * @param null|array<string,mixed> $categories
     * @param null|int $createdBy
     * @param int $limit
     * @param null|string $sortBy
     * @param null|string $sortDirection
     * @param null|int $page
     * @param null|int $perPage
     * @param null|array<string,mixed> $groupBy
     * @param null|string $searchTerm
     * @return void
     */
    public function __construct(
        public readonly ?string $priority = null,
        public readonly ?bool $completed = null,
        public readonly ?Carbon $dueDate = null,
        public readonly ?Carbon $dueDateStart = null,
        public readonly ?Carbon $dueDateEnd = null,
        public readonly ?array $priorities = null,
        public readonly ?string $assignee = null,
        public readonly ?array $assignees = null,
        public readonly ?string $category = null,
        public readonly ?array $categories = null,
        public readonly ?int $createdBy = null,
        public readonly int $limit = 4,
        ?string $sortBy = 'due_date',
        ?string $sortDirection = 'asc',
        ?int $page = 1,
        ?int $perPage = 15,
        ?array $groupBy = null,
        ?string $searchTerm = null
    ) {
        parent::__construct($sortBy, $sortDirection, $page, $perPage, $groupBy, $searchTerm);
    }

    /**
     * Create a new TaskFilterDTO from an array
     *
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['priority'] ?? null,
            isset($data['completed']) ? (bool)$data['completed'] : null,
            isset($data['dueDate']) ? Carbon::parse($data['dueDate']) : null,
            isset($data['dueDateStart']) ? Carbon::parse($data['dueDateStart']) : null,
            isset($data['dueDateEnd']) ? Carbon::parse($data['dueDateEnd']) : null,
            $data['priorities'] ?? null,
            $data['assignee'] ?? null,
            $data['assignees'] ?? null,
            $data['category'] ?? null,
            $data['categories'] ?? null,
            $data['createdBy'] ?? null,
            $data['limit'] ?? 4,
            $data['sortBy'] ?? 'due_date',
            $data['sortDirection'] ?? 'asc',
            $data['page'] ?? 1,
            $data['perPage'] ?? 15,
            $data['groupBy'] ?? null,
            $data['searchTerm'] ?? null
        );
    }

    /**
     * Convert the TaskFilterDTO to an array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'priority' => $this->priority,
            'completed' => $this->completed,
            'dueDate' => $this->dueDate?->toDateTimeString(),
            'dueDateStart' => $this->dueDateStart?->toDateTimeString(),
            'dueDateEnd' => $this->dueDateEnd?->toDateTimeString(),
            'priorities' => $this->priorities,
            'assignee' => $this->assignee,
            'assignees' => $this->assignees,
            'category' => $this->category,
            'categories' => $this->categories,
            'createdBy' => $this->createdBy,
            'limit' => $this->limit,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
            'page' => $this->page,
            'perPage' => $this->perPage,
            'groupBy' => $this->groupBy,
            'searchTerm' => $this->searchTerm,
        ];
    }
}
