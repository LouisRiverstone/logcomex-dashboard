<?php

namespace App\DTOs\Dashboard\Output;

class TrafficSourcesOutputDTO
{
    /**
     * Create a new TrafficSourcesOutputDTO
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
     * Create a new TrafficSourcesOutputDTO from an array
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
     * Convert the TrafficSourcesOutputDTO to an array
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
