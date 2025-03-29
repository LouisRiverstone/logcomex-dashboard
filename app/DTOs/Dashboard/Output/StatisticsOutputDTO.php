<?php

namespace App\DTOs\Dashboard\Output;

class StatisticsOutputDTO
{
    /**
     * Create a new StatisticsOutputDTO
     *
     * @param array<string,mixed> $statistics
     */
    public function __construct(
        public readonly array $statistics
    ) {
        // Construtor com propriedades definidas
    }

    /**
     * Create a new StatisticsOutputDTO from an array
     *
     * @param array<string,mixed> $statistics
     * @return self
     */
    public static function fromArray(array $statistics): self
    {
        return new self($statistics);
    }

    /**
     * Convert the StatisticsOutputDTO to an array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return $this->statistics;
    }
}
