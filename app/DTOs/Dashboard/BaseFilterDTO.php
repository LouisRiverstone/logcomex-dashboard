<?php

namespace App\DTOs\Dashboard;

abstract class BaseFilterDTO
{
    /**
     * Create a new BaseFilterDTO
     * @param null|string $sortBy
     * @param null|string $sortDirection
     * @param null|int $page
     * @param null|int $perPage
     * @param null|array<string,mixed> $groupBy
     * @param null|string $searchTerm
     * @return void
     */
    public function __construct(
        public readonly ?string $sortBy = null,
        public readonly ?string $sortDirection = 'asc',
        public readonly ?int $page = 1,
        public readonly ?int $perPage = 15,
        public readonly ?array $groupBy = null,
        public readonly ?string $searchTerm = null
    ) {
        // Construtor com propriedades definidas
    }

    /**
     * Get the pagination data
     * @return array<string,mixed>
     */
    public function getPaginationData(): array
    {
        return [
            'page' => $this->page,
            'perPage' => $this->perPage
        ];
    }

    /**
     * Get the sorting data
     * @return array<string,mixed>
     */
    public function getSortingData(): array
    {
        return [
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection
        ];
    }

    /**
     * Convert the BaseFilterDTO to an array
     * @return array<string,mixed>
     */
    abstract public function toArray(): array;
}
