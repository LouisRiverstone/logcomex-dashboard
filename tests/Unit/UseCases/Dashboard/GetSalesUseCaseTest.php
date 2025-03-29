<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\SalesOutputDTO;
use App\DTOs\Dashboard\SalesFilterDTO;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use App\UseCases\Dashboard\GetSalesUseCase;
use Tests\TestCase;
use Mockery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetSalesUseCaseTest extends TestCase
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

        // Sample sales data
        $salesData = [
            [
                'id' => 1,
                'customer' => 'João Silva',
                'product' => 'Smartphone',
                'date' => '2023-03-15',
                'amount' => 1200.00,
                'status' => 'completed',
            ],
            [
                'id' => 2,
                'customer' => 'Maria Santos',
                'product' => 'Laptop',
                'date' => '2023-03-16',
                'amount' => 3500.00,
                'status' => 'pending',
            ],
        ];

        // Create a paginator for the data
        $paginator = new LengthAwarePaginator(
            $salesData,
            count($salesData),
            10,
            1
        );

        // Create a filter
        $filter = new SalesFilterDTO();

        // Configure mock
        $mockRepo->shouldReceive('getSalesDashboardTable')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($paginator);

        $this->app->instance(SalesRepositoryInterface::class, $mockRepo);

        $useCase = new GetSalesUseCase($mockRepo);

        // Act
        $result = $useCase->execute($filter);

        // Assert
        $this->assertInstanceOf(SalesOutputDTO::class, $result);

        // Check metadata
        $this->assertEquals(count($salesData), $result->meta['total']);
        $this->assertEquals(10, $result->meta['per_page']);
        $this->assertEquals(1, $result->meta['current_page']);

        // Check data
        $this->assertCount(2, $result->data);
        $this->assertEquals(1, $result->data[0]['id']);
        $this->assertEquals('João Silva', $result->data[0]['customer']);
        $this->assertEquals('Smartphone', $result->data[0]['product']);

        $this->assertEquals(2, $result->data[1]['id']);
        $this->assertEquals('Maria Santos', $result->data[1]['customer']);
        $this->assertEquals('Laptop', $result->data[1]['product']);
    }
}
