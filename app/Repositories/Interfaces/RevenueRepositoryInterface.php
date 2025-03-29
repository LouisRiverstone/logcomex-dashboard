<?php

namespace App\Repositories\Interfaces;

use App\DTOs\Dashboard\RevenueFilterDTO;

interface RevenueRepositoryInterface
{
    /**
     * Get the monthly revenue by type
     *
     * @param string $period
     * @param null|string $dateStart
     * @param null|string $dateEnd
     * @return array<string,mixed>
     */
    public function getMonthlyRevenueByType(string $period, ?string $dateStart = null, ?string $dateEnd = null): array;

    /**
     * Get the filtered revenue
     *
     * @param RevenueFilterDTO $filter
     * @return array<string,mixed>
     */
    public function getFilteredRevenue(RevenueFilterDTO $filter): array;
}
