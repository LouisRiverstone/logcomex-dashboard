<?php

namespace App\DTOs\Dashboard;

use Carbon\Carbon;

class VisitorsFilterDTO extends BaseFilterDTO
{
    /**
     * Create a new VisitorsFilterDTO
     *
     * @param null|string $period
     * @param null|string $source
     * @param null|Carbon $startDate
     * @param null|Carbon $endDate
     * @param null|string $region
     * @param null|array<string,mixed> $sources
     * @param null|array<string,mixed> $regions
     * @param null|string $device
     * @param null|array<string,mixed> $devices
     * @param null|string $browser
     * @param null|array<string,mixed> $browsers
     * @param null|string $compareWith
     * @param null|string $sortBy
     * @param null|string $sortDirection
     * @param null|int $page
     * @param null|int $perPage
     * @param null|array<string,mixed> $groupBy
     * @param null|string $searchTerm
     * @return void
     */
    public function __construct(
        public readonly ?string $period = '30d',
        public readonly ?string $source = null,
        public readonly ?Carbon $startDate = null,
        public readonly ?Carbon $endDate = null,
        public readonly ?string $region = null,
        public readonly ?array $sources = null,
        public readonly ?array $regions = null,
        public readonly ?string $device = null,
        public readonly ?array $devices = null,
        public readonly ?string $browser = null,
        public readonly ?array $browsers = null,
        public readonly ?string $compareWith = null,
        ?string $sortBy = 'visited_at',
        ?string $sortDirection = 'desc',
        ?int $page = 1,
        ?int $perPage = 15,
        ?array $groupBy = null,
        ?string $searchTerm = null
    ) {
        parent::__construct($sortBy, $sortDirection, $page, $perPage, $groupBy, $searchTerm);
    }

    /**
     * Create a new VisitorsFilterDTO from an array
     *
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['period'] ?? '30d',
            $data['source'] ?? null,
            isset($data['startDate']) ? Carbon::parse($data['startDate']) : null,
            isset($data['endDate']) ? Carbon::parse($data['endDate']) : null,
            $data['region'] ?? null,
            $data['sources'] ?? null,
            $data['regions'] ?? null,
            $data['device'] ?? null,
            $data['devices'] ?? null,
            $data['browser'] ?? null,
            $data['browsers'] ?? null,
            $data['compareWith'] ?? null,
            $data['sortBy'] ?? 'visited_at',
            $data['sortDirection'] ?? 'desc',
            $data['page'] ?? 1,
            $data['perPage'] ?? 15,
            $data['groupBy'] ?? null,
            $data['searchTerm'] ?? null
        );
    }

    /**
     * Convert the VisitorsFilterDTO to an array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'period' => $this->period,
            'source' => $this->source,
            'startDate' => $this->startDate?->toDateTimeString(),
            'endDate' => $this->endDate?->toDateTimeString(),
            'region' => $this->region,
            'sources' => $this->sources,
            'regions' => $this->regions,
            'device' => $this->device,
            'devices' => $this->devices,
            'browser' => $this->browser,
            'browsers' => $this->browsers,
            'compareWith' => $this->compareWith,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
            'page' => $this->page,
            'perPage' => $this->perPage,
            'groupBy' => $this->groupBy,
            'searchTerm' => $this->searchTerm,
        ];
    }
}
