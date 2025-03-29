<?php

namespace App\DTOs\Dashboard\Output;

class SalesOutputDTO
{
    /**
     * @param array<string,mixed> $data
     * @param array<string,mixed> $meta
     */
    public function __construct(
        public readonly array $data,
        public readonly array $meta
    ) {
    }

    /**
     * @param array<string,array<array<string,mixed>|int>> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['data'],
            [
            'total' => $data['meta']['total'] ?? 0,
            'per_page' => $data['meta']['per_page'] ?? 0,
            'current_page' => $data['meta']['current_page'] ?? 0,
            'last_page' => $data['meta']['last_page'] ?? 0,
          ]
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'meta' => $this->meta,
        ];
    }
}
