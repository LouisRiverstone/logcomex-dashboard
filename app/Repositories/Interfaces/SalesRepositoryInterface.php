<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SalesRepositoryInterface
{
    /**
     * Get sales by category
     * @param string $year
     * @param int $limit
     * @return array<string,mixed>
     */
    public function getSalesByCategory(string $year, int $limit = 5): array;

    /**
     * Get sale details
     * @param int $saleId
     * @return object
     */
    public function viewSaleDetails(int $saleId): object;

    /**
     * Get sales dashboard table
     * @param array<string,mixed> $filters
     * @return LengthAwarePaginator<array<string,mixed>>
     */
    public function getSalesDashboardTable(array $filters = []): LengthAwarePaginator;

    /**
     * Get sales by region dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByRegionDashboardCharts(array $filters = []): array;

    /**
     * Get sales by user dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByUserDashboardCharts(array $filters = []): array;

    /**
     * Get sales by status dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByStatusDashboardCharts(array $filters = []): array;

    /**
     * Get sales by product dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByProductDashboardCharts(array $filters = []): array;

    /**
     * Get sales by category by months dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByCategoryByMouthsDashboardCharts(array $filters = []): array;
}
