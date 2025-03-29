<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\SalesDashboardOutputDTO;
use App\DTOs\Dashboard\SalesFilterDashboardDTO;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use App\Repositories\SalesRepository;

class GetSalesDashboardUseCase
{
    private SalesRepositoryInterface $salesRepository;

    public function __construct(
        ?SalesRepositoryInterface $salesRepository = null
    ) {
        $this->salesRepository = $salesRepository ?? new SalesRepository();
    }

    /**
     * Executa o caso de uso para obter os dados do dashboard de vendas
     *
     * @param SalesFilterDashboardDTO $filter
     * @return SalesDashboardOutputDTO
     */
    public function execute(SalesFilterDashboardDTO $filter): SalesDashboardOutputDTO
    {
        $salesByRegion = $this->salesRepository->getSalesByRegionDashboardCharts([
            'startDate' => $filter->startDate,
            'endDate' => $filter->endDate,
            'region' => $filter->region,
        ]);

        $salesByUser = $this->salesRepository->getSalesByUserDashboardCharts([
            'startDate' => $filter->startDate,
            'endDate' => $filter->endDate,
            'userName' => $filter->userName,
        ]);

        $salesByStatus = $this->salesRepository->getSalesByStatusDashboardCharts([
            'startDate' => $filter->startDate,
            'endDate' => $filter->endDate,
            'status' => $filter->status,
        ]);

        $salesByProduct = $this->salesRepository->getSalesByProductDashboardCharts([
            'startDate' => $filter->startDate,
            'endDate' => $filter->endDate,
            'productName' => $filter->productName,
        ]);

        $salesByMonth = $this->salesRepository->getSalesByCategoryByMouthsDashboardCharts([
            'startDate' => $filter->startDate,
            'endDate' => $filter->endDate,
            'categoryId' => $filter->categoryId,
        ]);

        return SalesDashboardOutputDTO::fromArray([
            'salesByRegion' => $salesByRegion,
            'salesByUser' => $salesByUser,
            'salesByStatus' => $salesByStatus,
            'salesByProduct' => $salesByProduct,
            'salesByMonth' => $salesByMonth,
        ]);
    }
}
