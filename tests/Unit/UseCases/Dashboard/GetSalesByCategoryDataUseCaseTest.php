<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\SalesByCategoryOutputDTO;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use App\UseCases\Dashboard\GetSalesByCategoryDataUseCase;
use Tests\TestCase;
use Mockery;

class GetSalesByCategoryDataUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecuteWithDefaultYear()
    {
        // Arrange
        $mockRepo = Mockery::mock(SalesRepositoryInterface::class);

        // Expected data returned from repository
        $salesByCategoryData = [
            ['name' => 'Eletrônicos', 'total' => 35],
            ['name' => 'Vestuário', 'total' => 25],
            ['name' => 'Alimentos', 'total' => 20],
            ['name' => 'Móveis', 'total' => 15],
            ['name' => 'Outros', 'total' => 5]
        ];

        // Expect getSalesByCategory to be called with current year
        $mockRepo->shouldReceive('getSalesByCategory')
            ->once()
            ->with(\Mockery::type('string'))
            ->andReturn($salesByCategoryData);

        $this->app->instance(SalesRepositoryInterface::class, $mockRepo);

        $useCase = new GetSalesByCategoryDataUseCase($mockRepo);

        // Act
        $result = $useCase->execute(null);

        // Assert
        $this->assertInstanceOf(SalesByCategoryOutputDTO::class, $result);

        // Check labels and data
        $expectedLabels = ['Eletrônicos', 'Vestuário', 'Alimentos', 'Móveis', 'Outros'];
        $expectedData = [35, 25, 20, 15, 5];

        $this->assertEquals($expectedLabels, $result->labels);
        $this->assertEquals($expectedData, $result->datasets['data']);

        // Check other dataset properties
        $this->assertArrayHasKey('backgroundColor', $result->datasets);
        $this->assertArrayHasKey('borderColor', $result->datasets);
        $this->assertArrayHasKey('borderWidth', $result->datasets);
    }

    public function testExecuteWithSpecificYear()
    {
        // Arrange
        $mockRepo = Mockery::mock(SalesRepositoryInterface::class);

        // Expected data returned from repository for a specific year
        $salesByCategoryData = [
            ['name' => 'Eletrônicos', 'total' => 30],
            ['name' => 'Vestuário', 'total' => 20],
            ['name' => 'Alimentos', 'total' => 25],
            ['name' => 'Móveis', 'total' => 20],
            ['name' => 'Outros', 'total' => 5]
        ];

        // Expect getSalesByCategory to be called with specified year
        $mockRepo->shouldReceive('getSalesByCategory')
            ->once()
            ->with('2022')
            ->andReturn($salesByCategoryData);

        $this->app->instance(SalesRepositoryInterface::class, $mockRepo);

        $useCase = new GetSalesByCategoryDataUseCase($mockRepo);

        // Act
        $result = $useCase->execute('2022');

        // Assert
        $this->assertInstanceOf(SalesByCategoryOutputDTO::class, $result);

        // Check labels and data
        $expectedLabels = ['Eletrônicos', 'Vestuário', 'Alimentos', 'Móveis', 'Outros'];
        $expectedData = [30, 20, 25, 20, 5];

        $this->assertEquals($expectedLabels, $result->labels);
        $this->assertEquals($expectedData, $result->datasets['data']);
    }
}
