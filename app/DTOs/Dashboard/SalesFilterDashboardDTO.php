<?php

namespace App\DTOs\Dashboard;

class SalesFilterDashboardDTO
{
    public function __construct(
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?string $userName = null,
        public readonly ?string $productName = null,
        public readonly ?int $categoryId = null,
        public readonly ?string $region = null,
        public readonly ?string $status = null
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
            isset($data['user_name']) ? (string)($data['user_name']) : null,
            isset($data['product_name']) ? (string) ($data['product_name']) : null,
            isset($data['category_id']) ? (int) ($data['category_id']) : null,
            isset($data['region']) ? (string) ($data['region']) : null,
            isset($data['status']) ? (string) ($data['status']) : null,
        );
    }

    /**
     * @return array<string,mixed>
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
            'status' => $this->status
        ];
    }
}
