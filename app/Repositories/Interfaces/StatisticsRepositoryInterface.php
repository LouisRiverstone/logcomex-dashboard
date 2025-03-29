<?php

namespace App\Repositories\Interfaces;

use Carbon\Carbon;

interface StatisticsRepositoryInterface
{
    /**
     * Get the active users
     *
     * @param Carbon $since
     * @return int
     */
    public function getActiveUsers(Carbon $since): int;

    /**
     * Get the new sales
     *
     * @param Carbon $since
     * @return float
     */
    public function getNewSales(Carbon $since): float;

    /**
     * Get the total revenue
     *
     * @param Carbon $since
     * @return float
     */
    public function getTotalRevenue(Carbon $since): float;

    /**
     * Get the conversion rate
     *
     * @param Carbon $since
     * @return float
     */
    public function getConversionRate(Carbon $since): float;

    /**
     * Get the user trend
     *
     * @param int $days
     * @return array<string,mixed>
     */
    public function getUserTrend(int $days = 30): array;

    /**
     * Get the sales trend
     *
     * @param int $days
     * @return array<float,int>
     */
    public function getSalesTrend(int $days = 30): array;

    /**
     * Get the revenue trend
     *
     * @param int $days
     * @return array<float,int>
     */
    public function getRevenueTrend(int $days = 30): array;

    /**
     * Get the conversion trend
     *
     * @param int $days
     * @return list<float>
     */
    public function getConversionTrend(int $days = 30): array;
}
