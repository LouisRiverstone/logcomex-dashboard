<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\TrafficSourcesOutputDTO;
use App\Repositories\Interfaces\VisitorsRepositoryInterface;
use App\Repositories\VisitorsRepository;

class GetTrafficSourcesDataUseCase
{
    private VisitorsRepositoryInterface $visitorsRepository;

    public function __construct(?VisitorsRepositoryInterface $visitorsRepository = null)
    {
        $this->visitorsRepository = $visitorsRepository ?? new VisitorsRepository();
    }

    public function execute(?string $period): TrafficSourcesOutputDTO
    {
        $period = $period ?? '30d';

        $dateStart = $period === 'all' ? now()->startOfCentury() : now()->sub($period);
        $dateEnd = now();

        $sources = $this->visitorsRepository->getVisitorsBySource($dateStart, $dateEnd);

        // Create associative arrays for labels and datasets
        $labelsArray = [];
        foreach (array_column($sources, 'source') as $key => $source) {
            $labelsArray['source_' . $key] = $source;
        }

        $datasetsArray = [
            'visitors' => [
                'data' => array_column($sources, 'count'),
                'backgroundColor' => [
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(99, 102, 241, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(236, 72, 153, 0.8)'
                ],
                'borderWidth' => 1,
                'borderColor' => '#ffffff',
                'hoverOffset' => 4
            ]
        ];

        return new TrafficSourcesOutputDTO(
            $labelsArray,
            $datasetsArray
        );
    }
}
