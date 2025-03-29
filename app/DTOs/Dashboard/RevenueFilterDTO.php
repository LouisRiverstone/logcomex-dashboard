<?php

namespace App\DTOs\Dashboard;

use Carbon\Carbon;

class RevenueFilterDTO
{
    /**
     * Create a new RevenueFilterDTO
     *
     * @param null|string $year
     * @param null|array<string,mixed> $types
     * @param null|string $period
     * @param null|Carbon $startDate
     * @param null|Carbon $endDate
     * @param null|string $comparison
    * @return void
    */
    public function __construct(
        public readonly ?string $year = null,
        public readonly ?array $types = null,
        public readonly ?string $period = null,
        public readonly ?Carbon $startDate = null,
        public readonly ?Carbon $endDate = null,
        public readonly ?string $comparison = null
    ) {
        // Construtor com propriedades definidas
    }

    /**
     * Create a new RevenueFilterDTO from an array
     *
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['year'] ?? date('Y'),
            $data['types'] ?? null,
            $data['period'] ?? null,
            isset($data['startDate']) ? Carbon::parse($data['startDate']) : null,
            isset($data['endDate']) ? Carbon::parse($data['endDate']) : null,
            $data['comparison'] ?? null
        );
    }

    /**
     * Convert the RevenueFilterDTO to an array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'year' => $this->year,
            'types' => $this->types,
            'period' => $this->period,
            'startDate' => $this->startDate?->toDateTimeString(),
            'endDate' => $this->endDate?->toDateTimeString(),
            'comparison' => $this->comparison,
        ];
    }
}
