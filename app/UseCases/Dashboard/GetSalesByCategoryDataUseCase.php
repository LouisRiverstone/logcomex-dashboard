<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\SalesByCategoryOutputDTO;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use App\Repositories\SalesRepository;

class GetSalesByCategoryDataUseCase
{
    private SalesRepositoryInterface $salesRepository;

    public function __construct(?SalesRepositoryInterface $salesRepository = null)
    {
        $this->salesRepository = $salesRepository ?? new SalesRepository();
    }

    public function execute(?string $period): SalesByCategoryOutputDTO
    {
        // Se o período for um ano, passa diretamente para o repositório
        if ($period !== null && preg_match('/^\d{4}$/', $period)) {
            $salesData = $this->salesRepository->getSalesByCategory($period);
        } else {
            // Caso contrário, usa a lógica de período relativo
            $period = $period ?? '30d';
            $dateStart = $period === 'all' ? now()->startOfCentury() : now()->sub($period);
            $salesData = $this->salesRepository->getSalesByCategory($dateStart);
        }

        $categories = collect($salesData);

        return new SalesByCategoryOutputDTO(
            $categories->pluck('name')->toArray(),
            [
                'data' => $categories->pluck('total')->toArray(),
                'backgroundColor' => [
                    'rgba(99, 102, 241, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(249, 115, 22, 0.7)',
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(236, 72, 153, 0.7)',
                    'rgba(6, 182, 212, 0.7)',
                    'rgba(34, 197, 94, 0.7)',
                ],
                'borderColor' => '#ffffff',
                'borderWidth' => 1
            ]
        );
    }
}
