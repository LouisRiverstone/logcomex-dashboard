<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\RevenueFilterDTO;
use App\DTOs\Dashboard\Output\RevenueDataOutputDTO;
use App\Repositories\Interfaces\RevenueRepositoryInterface;
use App\Repositories\RevenueRepository;

class GetRevenueDataUseCase
{
    private RevenueRepositoryInterface $revenueRepository;

    public function __construct(?RevenueRepositoryInterface $revenueRepository = null)
    {
        $this->revenueRepository = $revenueRepository ?? new RevenueRepository();
    }

    /**
     * @param RevenueFilterDTO|null $filter
     * @return RevenueDataOutputDTO
     */
    public function execute(?RevenueFilterDTO $filter): RevenueDataOutputDTO
    {
        $months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        // Usando o filtro se fornecido, caso contrário usando o método padrão
        $revenueByType = $filter
            ? $this->revenueRepository->getFilteredRevenue($filter)
            : $this->revenueRepository->getMonthlyRevenueByType(date('Y'));

        // Se for comparação entre períodos, formatar dados de forma diferente
        if ($filter && $filter->comparison === 'previous_year') {
            return $this->formatComparisonData($revenueByType, $months);
        }

        $servicesData = [];
        $productsData = [];
        $subscriptionsData = [];

        foreach (range(1, 12) as $month) {
            $servicesData[] = $revenueByType['service'][$month]['total'] ?? 0;
            $productsData[] = $revenueByType['product'][$month]['total'] ?? 0;
            $subscriptionsData[] = $revenueByType['subscription'][$month]['total'] ?? 0;
        }

        // Create associative array for labels
        $labelArray = [];
        foreach ($months as $index => $month) {
            $labelArray['month_' . $index] = $month;
        }

        // Create associative array for datasets
        $datasetsArray = [
            'services' => [
                'label' => 'Serviços',
                'data' => $servicesData,
                'borderColor' => '#10B981',
                'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                'tension' => 0.4,
                'fill' => true
            ],
            'products' => [
                'label' => 'Produtos',
                'data' => $productsData,
                'borderColor' => '#6366F1',
                'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                'tension' => 0.4,
                'fill' => true
            ],
            'subscriptions' => [
                'label' => 'Assinaturas',
                'data' => $subscriptionsData,
                'borderColor' => '#EC4899',
                'backgroundColor' => 'rgba(236, 72, 153, 0.1)',
                'tension' => 0.4,
                'fill' => true
            ]
        ];

        return new RevenueDataOutputDTO(
            $labelArray,
            $datasetsArray
        );
    }

    /**
     * Format data for comparison between periods
     *
     * @param array<string,array<string,array<int,array<string,float>>>> $comparisonData Comparison data
     * @param array<int,string> $months Month labels
     * @return RevenueDataOutputDTO
     */
    private function formatComparisonData(array $comparisonData, array $months): RevenueDataOutputDTO
    {
        $currentYear = date('Y');
        $previousYear = (int)$currentYear - 1;

        $currentYearData = [];
        $previousYearData = [];

        // Agregar todos os tipos para cada ano
        foreach (range(1, 12) as $month) {
            $currentMonthTotal = 0;
            $previousMonthTotal = 0;

            foreach (['service', 'product', 'subscription'] as $type) {
                $currentMonthTotal += $comparisonData['current'][$type][$month]['total'] ?? 0;
                $previousMonthTotal += $comparisonData['previous'][$type][$month]['total'] ?? 0;
            }

            $currentYearData[] = $currentMonthTotal;
            $previousYearData[] = $previousMonthTotal;
        }

        // Create associative array for labels
        $labelArray = [];
        foreach ($months as $index => $month) {
            $labelArray['month_' . $index] = $month;
        }

        // Create associative array for datasets
        $datasetsArray = [
            'current_year' => [
                'label' => 'Receita ' . $currentYear,
                'data' => $currentYearData,
                'borderColor' => '#6366F1',
                'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                'tension' => 0.4,
                'fill' => true
            ],
            'previous_year' => [
                'label' => 'Receita ' . $previousYear,
                'data' => $previousYearData,
                'borderColor' => '#94A3B8',
                'backgroundColor' => 'transparent',
                'tension' => 0.4,
                'borderDash' => [5, 5]
            ]
        ];

        return new RevenueDataOutputDTO(
            $labelArray,
            $datasetsArray
        );
    }
}
