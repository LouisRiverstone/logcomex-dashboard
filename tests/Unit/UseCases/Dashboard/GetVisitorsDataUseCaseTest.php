<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\VisitorsDataOutputDTO;
use App\DTOs\Dashboard\VisitorsFilterDTO;
use App\Repositories\Interfaces\VisitorsRepositoryInterface;
use App\UseCases\Dashboard\GetVisitorsDataUseCase;
use Carbon\Carbon;
use Tests\TestCase;
use Mockery;

class GetVisitorsDataUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecuteWithDefaultPeriod()
    {
        // Arrange
        $mockRepo = Mockery::mock(VisitorsRepositoryInterface::class);

        // Expected data returned from repository
        $currentYearData = [
            1 => ['count' => 100], 2 => ['count' => 150], 3 => ['count' => 200],
            4 => ['count' => 250], 5 => ['count' => 300], 6 => ['count' => 350],
            7 => ['count' => 400], 8 => ['count' => 450], 9 => ['count' => 500],
            10 => ['count' => 550], 11 => ['count' => 600], 12 => ['count' => 650]
        ];

        $lastYearData = [
            1 => ['count' => 50], 2 => ['count' => 75], 3 => ['count' => 100],
            4 => ['count' => 125], 5 => ['count' => 150], 6 => ['count' => 175],
            7 => ['count' => 200], 8 => ['count' => 225], 9 => ['count' => 250],
            10 => ['count' => 275], 11 => ['count' => 300], 12 => ['count' => 325]
        ];

        // Expect getVisitorsByPeriod to be called with appropriate dates for current and previous year
        $mockRepo->shouldReceive('getVisitorsByPeriod')
            ->twice()
            ->andReturn($currentYearData, $lastYearData);

        $this->app->instance(VisitorsRepositoryInterface::class, $mockRepo);

        $useCase = new GetVisitorsDataUseCase($mockRepo);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertInstanceOf(VisitorsDataOutputDTO::class, $result);

        // Check the months array
        $this->assertEquals(['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'], $result->labels);

        // Check the visitors data series
        $this->assertCount(2, $result->datasets);
        $this->assertEquals('Visitantes ' . date('Y'), $result->datasets[0]['label']);
        $this->assertEquals('Visitantes ' . (date('Y') - 1), $result->datasets[1]['label']);

        // Verify the data points
        $expectedCurrentYear = [100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650];
        $expectedLastYear = [50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300, 325];

        $this->assertEquals($expectedCurrentYear, $result->datasets[0]['data']);
        $this->assertEquals($expectedLastYear, $result->datasets[1]['data']);
    }

    public function testExecuteWithCustomFilter()
    {
        // Arrange
        $mockRepo = Mockery::mock(VisitorsRepositoryInterface::class);

        // Custom filter with source and region
        $filter = new VisitorsFilterDTO(
            '90d',
            'direct',
            Carbon::now()->subDays(90),
            Carbon::now(),
            'US'
        );

        // Expected filtered data
        $currentYearData = [
            1 => ['count' => 30], 2 => ['count' => 40], 3 => ['count' => 50],
            4 => ['count' => 60], 5 => ['count' => 70], 6 => ['count' => 80],
            7 => ['count' => 90], 8 => ['count' => 100], 9 => ['count' => 110],
            10 => ['count' => 120], 11 => ['count' => 130], 12 => ['count' => 140]
        ];

        $lastYearData = [
            1 => ['count' => 15], 2 => ['count' => 20], 3 => ['count' => 25],
            4 => ['count' => 30], 5 => ['count' => 35], 6 => ['count' => 40],
            7 => ['count' => 45], 8 => ['count' => 50], 9 => ['count' => 55],
            10 => ['count' => 60], 11 => ['count' => 65], 12 => ['count' => 70]
        ];

        // Expect getFilteredVisitors to be called with appropriate filters
        $mockRepo->shouldReceive('getFilteredVisitors')
            ->twice()
            ->andReturn($currentYearData, $lastYearData);

        $this->app->instance(VisitorsRepositoryInterface::class, $mockRepo);

        $useCase = new GetVisitorsDataUseCase($mockRepo);

        // Act
        $result = $useCase->execute($filter);

        // Assert
        $this->assertInstanceOf(VisitorsDataOutputDTO::class, $result);

        // Check the visitors data series - specific to this filter
        $expectedCurrentYear = [30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140];
        $expectedLastYear = [15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70];

        $this->assertEquals($expectedCurrentYear, $result->datasets[0]['data']);
        $this->assertEquals($expectedLastYear, $result->datasets[1]['data']);
    }
}
