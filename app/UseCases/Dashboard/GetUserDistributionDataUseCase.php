<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\UserDistributionOutputDTO;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

class GetUserDistributionDataUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(?UserRepositoryInterface $userRepository = null)
    {
        $this->userRepository = $userRepository ?? new UserRepository();
    }

    public function execute(?string $period): UserDistributionOutputDTO
    {
        $period = $period ?? '30d';

        $dateStart = $period === 'all' ? now()->startOfCentury() : now()->sub($period);

        $dateEnd = now();

        $regions = $this->userRepository->getDistributionByRegion($dateStart, $dateEnd);

        // Create associative arrays for labels and datasets
        $labelsArray = [];
        foreach (array_column($regions, 'region') as $key => $region) {
            $labelsArray['region_' . $key] = $region;
        }

        $datasetsArray = [
            'users' => [
                'data' => array_column($regions, 'count'),
                'backgroundColor' => [
                    'rgba(99, 102, 241, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(236, 72, 153, 0.8)'
                ],
                'borderWidth' => 1,
                'borderColor' => '#ffffff',
                'hoverOffset' => 4
            ]
        ];

        return new UserDistributionOutputDTO(
            $labelsArray,
            $datasetsArray
        );
    }
}
