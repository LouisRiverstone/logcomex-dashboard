<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\StatisticsOutputDTO;
use App\Repositories\Interfaces\StatisticsRepositoryInterface;
use App\Repositories\StatisticsRepository;
use Carbon\Carbon;

class GetStatisticsUseCase
{
    private StatisticsRepositoryInterface $statisticsRepository;

    public function __construct(?StatisticsRepositoryInterface $statisticsRepository = null)
    {
        $this->statisticsRepository = $statisticsRepository ?? new StatisticsRepository();
    }

    public function execute(): StatisticsOutputDTO
    {
        $lastMonth = Carbon::now()->subMonth();
        $lastWeek = Carbon::now()->subWeek();
        $yesterday = Carbon::now()->subDay();
        $twoMonthsAgo = Carbon::now()->subMonths(2);
        $twoWeeksAgo = Carbon::now()->subWeeks(2);
        $twoDaysAgo = Carbon::now()->subDays(2);

        // Usuários ativos
        $activeUsers = $this->statisticsRepository->getActiveUsers($lastMonth);
        $prevMonthActiveUsers = $this->statisticsRepository->getActiveUsers($twoMonthsAgo) -
                                $this->statisticsRepository->getActiveUsers($lastMonth);
        $userChangePercent = $prevMonthActiveUsers > 0
            ? round(($activeUsers - $prevMonthActiveUsers) / $prevMonthActiveUsers * 100, 1)
            : 0;

        // Vendas novas
        $newSales = $this->statisticsRepository->getNewSales($lastWeek);
        $prevWeekSales = $this->statisticsRepository->getNewSales($twoWeeksAgo) -
                          $this->statisticsRepository->getNewSales($lastWeek);
        $salesChangePercent = $prevWeekSales > 0
            ? round(($newSales - $prevWeekSales) / $prevWeekSales * 100, 1)
            : 0;

        // Receita total
        $totalRevenue = $this->statisticsRepository->getTotalRevenue($lastMonth);
        $prevMonthRevenue = $this->statisticsRepository->getTotalRevenue($twoMonthsAgo) -
                             $this->statisticsRepository->getTotalRevenue($lastMonth);
        $revenueChangePercent = $prevMonthRevenue > 0
            ? round(($totalRevenue - $prevMonthRevenue) / $prevMonthRevenue * 100, 1)
            : 0;

        // Taxa de conversão
        $conversionRate = $this->statisticsRepository->getConversionRate($yesterday);
        $prevConversionRate = $this->statisticsRepository->getConversionRate($twoDaysAgo);
        $conversionChangePercent = $prevConversionRate > 0
            ? round(($conversionRate - $prevConversionRate) / $prevConversionRate * 100, 1)
            : 0;

        // Dados para sparklines
        $userTrend = $this->statisticsRepository->getUserTrend();
        $salesTrend = $this->statisticsRepository->getSalesTrend();
        $revenueTrend = $this->statisticsRepository->getRevenueTrend();
        $conversionTrend = $this->statisticsRepository->getConversionTrend();

        $statistics = [
            'active_users' => [
                'title' => 'Usuários Ativos',
                'value' => number_format($activeUsers, 0, ',', '.'),
                'change' => [
                    'value' => ($userChangePercent >= 0 ? '+' : '') . $userChangePercent . '%',
                    'type' => $userChangePercent >= 0 ? 'success' : 'danger'
                ],
                'trend' => [
                    'direction' => $userChangePercent >= 0 ? 'up' : 'down',
                    'value' => $userChangePercent
                ],
                'period' => 'Desde o último mês',
                'sparklineData' => $userTrend
            ],
            'new_sales' => [
                'title' => 'Novas Vendas',
                'value' => 'R$ ' . number_format($newSales, 2, ',', '.'),
                'change' => [
                    'value' => ($salesChangePercent >= 0 ? '+' : '') . $salesChangePercent . '%',
                    'type' => $salesChangePercent >= 0 ? 'success' : 'danger'
                ],
                'trend' => [
                    'direction' => $salesChangePercent >= 0 ? 'up' : 'down',
                    'value' => $salesChangePercent
                ],
                'period' => 'Desde a semana passada',
                'sparklineData' => $salesTrend
            ],
            'total_revenue' => [
                'title' => 'Receita Total',
                'value' => 'R$ ' . number_format($totalRevenue, 2, ',', '.'),
                'change' => [
                    'value' => ($revenueChangePercent >= 0 ? '+' : '') . $revenueChangePercent . '%',
                    'type' => $revenueChangePercent >= 0 ? 'success' : 'danger'
                ],
                'trend' => [
                    'direction' => $revenueChangePercent >= 0 ? 'up' : 'down',
                    'value' => $revenueChangePercent
                ],
                'period' => 'Desde o último mês',
                'sparklineData' => $revenueTrend
            ],
            'conversion_rate' => [
                'title' => 'Taxa de Conversão',
                'value' => $conversionRate . '%',
                'change' => [
                    'value' => ($conversionChangePercent >= 0 ? '+' : '') . $conversionChangePercent . '%',
                    'type' => $conversionChangePercent >= 0 ? 'success' : 'danger'
                ],
                'trend' => [
                    'direction' => $conversionChangePercent >= 0 ? 'up' : 'down',
                    'value' => $conversionChangePercent
                ],
                'period' => 'Desde ontem',
                'sparklineData' => $conversionTrend
            ]
        ];

        return new StatisticsOutputDTO($statistics);
    }
}
