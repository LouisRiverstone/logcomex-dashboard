<?php

namespace App\DTOs\Dashboard\Output;

class UserDistributionOutputDTO
{
    /**
     * Create a new UserDistributionOutputDTO
     *
     * @param array<string,mixed> $labels
     * @param array<string,mixed> $datasets
     */
    public function __construct(
        public readonly array $labels,
        public readonly array $datasets
    ) {
        // Construtor com propriedades definidas
    }

    /**
     * Create a new UserDistributionOutputDTO from an array
     *
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['labels'] ?? [],
            $data['datasets'] ?? []
        );
    }

    /**
     * Convert the UserDistributionOutputDTO to an array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'labels' => $this->labels,
            'datasets' => $this->datasets
        ];
    }
}
