<?php

namespace App\DTOs\Dashboard;

class SalesFilterDTO
{
    public function __construct(
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?string $userName = null,
        public readonly ?string $productName = null,
        public readonly ?int $categoryId = null,
        public readonly ?string $region = null,
        public readonly ?string $status = null,
        public readonly ?int $page = 1,
        public readonly ?int $perPage = 15,
        public readonly ?string $orderBy = 'created_at',
        public readonly ?string $orderDirection = 'desc',
    ) {
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['start_date']) ? (string) ($data['start_date']) : null,
            isset($data['end_date']) ? (string) ($data['end_date']) : null,
            isset($data['user_name']) ? (string) ($data['user_name']) : null,
            isset($data['product_name']) ? (string) ($data['product_name']) : null,
            isset($data['category_id']) ? (int) ($data['category_id']) : null,
            isset($data['region']) ? (string) ($data['region']) : null,
            isset($data['status']) ? (string) ($data['status']) : null,
            isset($data['page']) ? (int) ($data['page']) : 1,
            isset($data['per_page']) ? (int) ($data['per_page']) : 15,
            isset($data['order_by']) ? (string) ($data['order_by']) : 'created_at',
            isset($data['order_direction']) ? (string) ($data['order_direction']) : 'desc',
        );
    }

    /**
     * @return array<string,string|int|null>
     */
    public function toArray(): array
    {
        return [
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'user_name' => $this->userName,
            'product_name' => $this->productName,
            'category_id' => $this->categoryId,
            'region' => $this->region,
            'status' => $this->status,
            'page' => $this->page,
            'per_page' => $this->perPage,
            'order_by' => $this->orderBy,
            'order_direction' => $this->orderDirection,
        ];
    }
}
