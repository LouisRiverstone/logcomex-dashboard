<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\VisitorsFilterDTO;
use App\DTOs\Dashboard\Output\VisitorsDataOutputDTO;
use App\Repositories\Interfaces\VisitorsRepositoryInterface;
use App\Repositories\VisitorsRepository;
use Carbon\Carbon;

class GetVisitorsDataUseCase
{
    private VisitorsRepositoryInterface $visitorsRepository;

    public function __construct(?VisitorsRepositoryInterface $visitorsRepository = null)
    {
        $this->visitorsRepository = $visitorsRepository ?? new VisitorsRepository();
    }

    public function execute(string|VisitorsFilterDTO $period = '30d'): VisitorsDataOutputDTO
    {
        // Converter o parâmetro para DTO se for uma string
        $filter = is_string($period)
            ? new VisitorsFilterDTO($period)
            : $period;

        $days = match($filter->period) {
            '7d' => 7,
            '90d' => 90,
            default => 30
        };

        $startDate = $filter->startDate ?? Carbon::now()->subDays($days)->startOfDay();
        $endDate = $filter->endDate ?? Carbon::now()->endOfDay();

        // Visitantes utilizando o método filtrado
        $currentYearVisitors = $filter->source || $filter->region
            ? $this->visitorsRepository->getFilteredVisitors($filter)
            : $this->visitorsRepository->getVisitorsByPeriod($startDate, $endDate);

        // Visitantes do ano passado (mesmo período)
        $lastYearStart = Carbon::now()->subYear()->subDays($days)->startOfDay();
        $lastYearEnd = Carbon::now()->subYear()->endOfDay();

        $lastYearFilter = new VisitorsFilterDTO(
            $filter->period,
            $filter->source,
            $lastYearStart,
            $lastYearEnd,
            $filter->region
        );

        $lastYearVisitors = $filter->source || $filter->region
            ? $this->visitorsRepository->getFilteredVisitors($lastYearFilter)
            : $this->visitorsRepository->getVisitorsByPeriod($lastYearStart, $lastYearEnd);

        $months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $currentYearData = [];
        $lastYearData = [];

        foreach (range(1, 12) as $month) {
            $currentYearData[] = $currentYearVisitors[$month]['count'] ?? 0;
            $lastYearData[] = $lastYearVisitors[$month]['count'] ?? 0;
        }

        // Create associative arrays for labels and datasets
        $labelsArray = [];
        foreach ($months as $index => $month) {
            $labelsArray['month_' . $index] = $month;
        }

        $datasetsArray = [
            'current_year' => [
                'label' => 'Visitantes ' . date('Y'),
                'data' => $currentYearData,
                'borderColor' => '#6366F1',
                'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                'tension' => 0.4,
                'fill' => true
            ],
            'previous_year' => [
                'label' => 'Visitantes ' . (date('Y') - 1),
                'data' => $lastYearData,
                'borderColor' => '#94A3B8',
                'backgroundColor' => 'transparent',
                'tension' => 0.4,
                'borderDash' => [5, 5]
            ]
        ];

        return new VisitorsDataOutputDTO(
            $labelsArray,
            $datasetsArray
        );
    }
}
