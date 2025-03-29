<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\RevenueDataOutputDTO;
use App\DTOs\Dashboard\RevenueFilterDTO;
use App\Repositories\Interfaces\RevenueRepositoryInterface;
use App\UseCases\Dashboard\GetRevenueDataUseCase;
use Tests\TestCase;
use Mockery;

class GetRevenueDataUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecuteWithDefaultPeriod()
    {
        // Arrange
        $mockRepo = Mockery::mock(RevenueRepositoryInterface::class);

        // Set up mock methods to match actual repository methods
        $mockRepo->shouldReceive('getFilteredRevenue')
            ->andReturn([
                'service' => [
                    1 => ['total' => 10000], 2 => ['total' => 12000], 3 => ['total' => 15000],
                    4 => ['total' => 18000], 5 => ['total' => 20000], 6 => ['total' => 22000],
                    7 => ['total' => 25000], 8 => ['total' => 27000], 9 => ['total' => 30000],
                    10 => ['total' => 32000], 11 => ['total' => 35000], 12 => ['total' => 37000]
                ],
                'product' => [
                    1 => ['total' => 20000], 2 => ['total' => 23000], 3 => ['total' => 25000],
                    4 => ['total' => 27000], 5 => ['total' => 30000], 6 => ['total' => 32000],
                    7 => ['total' => 35000], 8 => ['total' => 37000], 9 => ['total' => 40000],
                    10 => ['total' => 42000], 11 => ['total' => 45000], 12 => ['total' => 47000]
                ],
                'subscription' => [
                    1 => ['total' => 20000], 2 => ['total' => 20000], 3 => ['total' => 20000],
                    4 => ['total' => 20000], 5 => ['total' => 20000], 6 => ['total' => 21000],
                    7 => ['total' => 20000], 8 => ['total' => 21000], 9 => ['total' => 20000],
                    10 => ['total' => 21000], 11 => ['total' => 20000], 12 => ['total' => 21000]
                ]
            ]);

        $mockRepo->shouldReceive('getMonthlyRevenueByType')
            ->with(date('Y'))
            ->andReturn([
                'service' => [
                    1 => ['total' => 10000], 2 => ['total' => 12000], 3 => ['total' => 15000],
                    4 => ['total' => 18000], 5 => ['total' => 20000], 6 => ['total' => 22000],
                    7 => ['total' => 25000], 8 => ['total' => 27000], 9 => ['total' => 30000],
                    10 => ['total' => 32000], 11 => ['total' => 35000], 12 => ['total' => 37000]
                ],
                'product' => [
                    1 => ['total' => 20000], 2 => ['total' => 23000], 3 => ['total' => 25000],
                    4 => ['total' => 27000], 5 => ['total' => 30000], 6 => ['total' => 32000],
                    7 => ['total' => 35000], 8 => ['total' => 37000], 9 => ['total' => 40000],
                    10 => ['total' => 42000], 11 => ['total' => 45000], 12 => ['total' => 47000]
                ],
                'subscription' => [
                    1 => ['total' => 20000], 2 => ['total' => 20000], 3 => ['total' => 20000],
                    4 => ['total' => 20000], 5 => ['total' => 20000], 6 => ['total' => 21000],
                    7 => ['total' => 20000], 8 => ['total' => 21000], 9 => ['total' => 20000],
                    10 => ['total' => 21000], 11 => ['total' => 20000], 12 => ['total' => 21000]
                ]
            ]);

        $this->app->instance(RevenueRepositoryInterface::class, $mockRepo);

        $useCase = new GetRevenueDataUseCase($mockRepo);

        // Create filter with default period
        $filter = new RevenueFilterDTO(
            null,     // year
            null,     // types
            '30d',    // period
            null,     // startDate
            null      // endDate
        );

        // Act
        $result = $useCase->execute($filter);

        // Assert
        $this->assertInstanceOf(RevenueDataOutputDTO::class, $result);

        // Check that we have labels
        $this->assertIsArray($result->labels);

        // Check that we have datasets
        $this->assertIsArray($result->datasets);
        $this->assertGreaterThan(0, count($result->datasets));

        // Check that each dataset has the expected structure
        foreach ($result->datasets as $dataset) {
            $this->assertArrayHasKey('label', $dataset);
            $this->assertArrayHasKey('data', $dataset);
            $this->assertArrayHasKey('borderColor', $dataset);
            $this->assertArrayHasKey('backgroundColor', $dataset);
        }
    }

    public function testExecuteWithYearAndTypes()
    {
        // Arrange
        $mockRepo = Mockery::mock(RevenueRepositoryInterface::class);

        // Set up mock for filtered revenue
        $mockRepo->shouldReceive('getFilteredRevenue')
            ->andReturn([
                'current' => [
                    'service' => [
                        1 => ['total' => 10000], 2 => ['total' => 12000], // ...truncated for brevity
                    ],
                    'product' => [
                        1 => ['total' => 20000], 2 => ['total' => 23000], // ...truncated for brevity
                    ],
                    'subscription' => [
                        1 => ['total' => 20000], 2 => ['total' => 20000], // ...truncated for brevity
                    ]
                ],
                'previous' => [
                    'service' => [
                        1 => ['total' => 8000], 2 => ['total' => 9000], // ...truncated for brevity
                    ],
                    'product' => [
                        1 => ['total' => 18000], 2 => ['total' => 20000], // ...truncated for brevity
                    ],
                    'subscription' => [
                        1 => ['total' => 18000], 2 => ['total' => 18000], // ...truncated for brevity
                    ]
                ]
            ]);

        $this->app->instance(RevenueRepositoryInterface::class, $mockRepo);

        $useCase = new GetRevenueDataUseCase($mockRepo);

        // Create filter with year, types and comparison
        $filter = new RevenueFilterDTO(
            '2023',                    // year
            ['subscription', 'product'], // types
            null,                      // period
            null,                      // startDate
            null,                      // endDate
            'previous_year'            // comparison
        );

        // Act
        $result = $useCase->execute($filter);

        // Assert
        $this->assertInstanceOf(RevenueDataOutputDTO::class, $result);

        // Check that we have labels
        $this->assertIsArray($result->labels);

        // Check that we have datasets
        $this->assertIsArray($result->datasets);
        $this->assertGreaterThan(0, count($result->datasets));

        // Check that each dataset has the expected structure
        foreach ($result->datasets as $dataset) {
            $this->assertArrayHasKey('label', $dataset);
            $this->assertArrayHasKey('data', $dataset);
            $this->assertArrayHasKey('borderColor', $dataset);
            $this->assertArrayHasKey('backgroundColor', $dataset);
        }
    }
}
