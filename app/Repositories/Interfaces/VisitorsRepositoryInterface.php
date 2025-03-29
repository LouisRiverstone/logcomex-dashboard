<?php

namespace App\Repositories\Interfaces;

use App\DTOs\Dashboard\VisitorsFilterDTO;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

interface VisitorsRepositoryInterface
{
    /**
     * Get the visitors by period
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array<string,mixed>
     */
    public function getVisitorsByPeriod(Carbon $startDate, Carbon $endDate): array;

    /**
     * Get the visitors by source
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array<string,mixed>
     */
    public function getVisitorsBySource(Carbon $startDate, Carbon $endDate): array;

    /**
     * Get the filtered visitors
     *
     * @param VisitorsFilterDTO $filter
     * @return array<string,mixed>|LengthAwarePaginator<object>
     */
    public function getFilteredVisitors(VisitorsFilterDTO $filter): array|LengthAwarePaginator;
}
