<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\SalesDashboardOutputDTO;
use App\DTOs\Dashboard\SalesFilterDashboardDTO;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use App\UseCases\Dashboard\GetSalesDashboardUseCase;
use Tests\TestCase;
use Mockery;

class GetSalesDashboardUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute()
    {
        // Arrange
        $mockRepo = Mockery::mock(SalesRepositoryInterface::class);

        // Sample data for charts
        $salesByRegion = [
            ['region' => 'Sudeste', 'total' => 45000],
            ['region' => 'Nordeste', 'total' => 25000],
            ['region' => 'Sul', 'total' => 20000],
        ];

        $salesByUser = [
            ['user' => 'João Silva', 'total' => 35000],
            ['user' => 'Maria Santos', 'total' => 28000],
            ['user' => 'Pedro Oliveira', 'total' => 22000],
        ];

        $salesByStatus = [
            ['status' => 'completed', 'total' => 75000],
            ['status' => 'pending', 'total' => 15000],
            ['status' => 'cancelled', 'total' => 5000],
        ];

        $salesByProduct = [
            ['product' => 'Smartphone', 'total' => 50000],
            ['product' => 'Laptop', 'total' => 35000],
            ['product' => 'Tablet', 'total' => 15000],
        ];

        $salesByMonth = [
            'Jan' => ['Eletrônicos' => 8000, 'Vestuário' => 5000, 'Alimentos' => 3000],
            'Fev' => ['Eletrônicos' => 9500, 'Vestuário' => 4800, 'Alimentos' => 3200],
            'Mar' => ['Eletrônicos' => 8800, 'Vestuário' => 5200, 'Alimentos' => 3500],
        ];

        // Create a filter
        $filter = new SalesFilterDashboardDTO();

        // Configure mocks
        $mockRepo->shouldReceive('getSalesByRegionDashboardCharts')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($salesByRegion);

        $mockRepo->shouldReceive('getSalesByUserDashboardCharts')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($salesByUser);

        $mockRepo->shouldReceive('getSalesByStatusDashboardCharts')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($salesByStatus);

        $mockRepo->shouldReceive('getSalesByProductDashboardCharts')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($salesByProduct);

        $mockRepo->shouldReceive('getSalesByCategoryByMouthsDashboardCharts')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($salesByMonth);

        $this->app->instance(SalesRepositoryInterface::class, $mockRepo);

        $useCase = new GetSalesDashboardUseCase($mockRepo);

        // Act
        $result = $useCase->execute($filter);

        // Assert
        $this->assertInstanceOf(SalesDashboardOutputDTO::class, $result);

        // Check the data in the result
        $this->assertEquals($salesByRegion, $result->salesByRegion);
        $this->assertEquals($salesByUser, $result->salesByUser);
        $this->assertEquals($salesByStatus, $result->salesByStatus);
        $this->assertEquals($salesByProduct, $result->salesByProduct);
        $this->assertEquals($salesByMonth, $result->salesByMonth);
    }
}
