<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\SalesOutputDTO;
use App\DTOs\Dashboard\SalesFilterDTO;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use App\Repositories\SalesRepository;

class GetSalesUseCase
{
    private SalesRepositoryInterface $salesRepository;

    public function __construct(
        ?SalesRepositoryInterface $salesRepository = null
    ) {
        $this->salesRepository = $salesRepository ?? new SalesRepository();
    }

    /**
     * @param SalesFilterDTO $filter
     * @return SalesOutputDTO
     */
    public function execute(SalesFilterDTO $filter): SalesOutputDTO
    {
        $dashboard = $this->salesRepository->getSalesDashboardTable([
            'startDate' => $filter->startDate,
            'endDate' => $filter->endDate,
            'userName' => $filter->userName,
            'productName' => $filter->productName,
            'categoryId' => $filter->categoryId,
            'region' => $filter->region,
            'status' => $filter->status,
            'page' => $filter->page,
            'perPage' => $filter->perPage,
            'orderBy' => $filter->orderBy,
            'orderDirection' => $filter->orderDirection,
        ]);

        return SalesOutputDTO::fromArray([
            'data' => $dashboard->items(),
            'meta' => [
                'total' => $dashboard->total(),
                'per_page' => $dashboard->perPage(),
                'current_page' => $dashboard->currentPage(),
                'last_page' => $dashboard->lastPage(),
            ],
        ]);
    }
}
