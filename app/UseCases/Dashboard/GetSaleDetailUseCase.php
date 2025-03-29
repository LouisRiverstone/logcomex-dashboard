<?php

namespace App\UseCases\Dashboard;

use App\Repositories\Interfaces\SalesRepositoryInterface;
use App\Repositories\SalesRepository;

class GetSaleDetailUseCase
{
    private SalesRepositoryInterface $salesRepository;

    public function __construct(
        ?SalesRepositoryInterface $salesRepository = null
    ) {
        $this->salesRepository = $salesRepository ?? new SalesRepository();
    }

    /**
     * Execute the use case
     *
     * @param int $saleId
     * @return object
     */
    public function execute(int $saleId): object
    {
        return $this->salesRepository->viewSaleDetails($saleId);
    }
}
