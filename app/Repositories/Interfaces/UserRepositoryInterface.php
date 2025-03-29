<?php

namespace App\Repositories\Interfaces;

use Carbon\Carbon;

interface UserRepositoryInterface
{
    /**
     * Get distribution by region
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array<string,mixed>
     */
    public function getDistributionByRegion(Carbon $startDate, Carbon $endDate): array;
}
