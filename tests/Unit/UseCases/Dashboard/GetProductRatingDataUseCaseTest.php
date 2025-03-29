<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\ProductRatingOutputDTO;
use App\DTOs\Dashboard\ProductRatingFilterDTO;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\UseCases\Dashboard\GetProductRatingDataUseCase;
use Tests\TestCase;
use Mockery;

class GetProductRatingDataUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecuteWithoutFilter()
    {
        // Arrange
        $mockRepo = Mockery::mock(ProductRepositoryInterface::class);

        // Sample product ratings data
        $currentProductRatings = (object)[
            'quality' => 4.5,
            'price' => 3.8,
            'usability' => 4.2,
            'design' => 4.7,
            'support' => 3.9,
            'features' => 4.3
        ];

        $competitorRatings = (object)[
            'quality' => 4.0,
            'price' => 4.1,
            'usability' => 3.8,
            'design' => 4.2,
            'support' => 3.5,
            'features' => 3.9
        ];

        // Configure mock
        $mockRepo->shouldReceive('getProductRatings')
            ->with(1)
            ->once()
            ->andReturn($currentProductRatings);

        $mockRepo->shouldReceive('getProductRatings')
            ->with(2)
            ->once()
            ->andReturn($competitorRatings);

        $this->app->instance(ProductRepositoryInterface::class, $mockRepo);

        $useCase = new GetProductRatingDataUseCase($mockRepo);

        // Act
        $result = $useCase->execute(null);

        // Assert
        $this->assertInstanceOf(ProductRatingOutputDTO::class, $result);

        // Check attributes
        $expectedAttributes = ['Qualidade', 'Preço', 'Usabilidade', 'Design', 'Suporte', 'Funcionalidades'];
        $this->assertEquals($expectedAttributes, $result->labels);

        // Check datasets
        $this->assertCount(2, $result->datasets);

        // Check current product data
        $this->assertEquals('Produto Atual', $result->datasets[0]['label']);
        $this->assertEquals([4.5, 3.8, 4.2, 4.7, 3.9, 4.3], $result->datasets[0]['data']);

        // Check competitor data
        $this->assertEquals('Concorrente', $result->datasets[1]['label']);
        $this->assertEquals([4.0, 4.1, 3.8, 4.2, 3.5, 3.9], $result->datasets[1]['data']);
    }

    public function testExecuteWithFilter()
    {
        // Arrange
        $mockRepo = Mockery::mock(ProductRepositoryInterface::class);

        // Create a filter with custom attributes
        $filter = new ProductRatingFilterDTO(
            attributes: ['Eficiência', 'Custo-benefício', 'Facilidade']
        );

        // Sample filtered ratings data
        $ratingData = [
            'product' => (object)[
                'quality' => 4.8,
                'price' => 4.0,
                'usability' => 4.5,
                'design' => 4.9,
                'support' => 4.2,
                'features' => 4.6
            ],
            'competitor' => (object)[
                'quality' => 4.2,
                'price' => 4.3,
                'usability' => 4.0,
                'design' => 4.4,
                'support' => 3.8,
                'features' => 4.1
            ]
        ];

        // Configure mock
        $mockRepo->shouldReceive('getFilteredProductRatings')
            ->with($filter)
            ->once()
            ->andReturn($ratingData);

        $this->app->instance(ProductRepositoryInterface::class, $mockRepo);

        $useCase = new GetProductRatingDataUseCase($mockRepo);

        // Act
        $result = $useCase->execute($filter);

        // Assert
        $this->assertInstanceOf(ProductRatingOutputDTO::class, $result);

        // Check custom attributes
        $expectedAttributes = ['Eficiência', 'Custo-benefício', 'Facilidade'];
        $this->assertEquals($expectedAttributes, $result->labels);

        // Check datasets
        $this->assertCount(2, $result->datasets);

        // Check styling attributes
        $this->assertArrayHasKey('borderColor', $result->datasets[0]);
        $this->assertArrayHasKey('backgroundColor', $result->datasets[0]);
        $this->assertArrayHasKey('pointRadius', $result->datasets[0]);
    }
}
