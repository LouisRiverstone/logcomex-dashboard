<?php

namespace App\DTOs\Dashboard;

class TransactionFilterDTO extends BaseFilterDTO
{
    /**
     * Create a new TransactionFilterDTO
     *
     * @param null|string $type
     * @param null|string $status
     * @param null|string $startDate
     * @param null|string $endDate
     * @param null|float $minAmount
     * @param null|float $maxAmount
     * @param null|string $customer
     * @param null|string $email
     * @param null|string $description
     * @param null|string $sortBy
     * @param null|string $sortDirection
     * @param null|int $page
     * @param null|int $perPage
     * @return void
     */
    public function __construct(
        public readonly ?string $type = null,
        public readonly ?string $status = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
        public readonly ?float $minAmount = null,
        public readonly ?float $maxAmount = null,
        public readonly ?string $customer = null,
        public readonly ?string $email = null,
        public readonly ?string $description = null,
        public readonly ?string $sortBy = 'created_at',
        public readonly ?string $sortDirection = 'desc',
        public readonly ?int $page = 1,
        public readonly ?int $perPage = 15
    ) {

    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['type'] ?? null,
            $data['status'] ?? null,
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['min_amount'] ?? null,
            $data['max_amount'] ?? null,
            $data['customer'] ?? null,
            $data['email'] ?? null,
            $data['description'] ?? null,
            $data['sort_by'] ?? 'created_at',
            $data['sort_direction'] ?? 'desc',
            $data['page'] ?? 1,
            $data['per_page'] ?? 15
        );
    }

    /**
     * @return array<string,string|int|float|null>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'status' => $this->status,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'min_amount' => $this->minAmount,
            'max_amount' => $this->maxAmount,
            'customer' => $this->customer,
            'email' => $this->email,
            'description' => $this->description,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
            'page' => $this->page,
            'per_page' => $this->perPage
        ];
    }
}
