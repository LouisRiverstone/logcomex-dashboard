<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\UserDistributionOutputDTO;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\UseCases\Dashboard\GetUserDistributionDataUseCase;
use Tests\TestCase;
use Mockery;

class GetUserDistributionDataUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute()
    {
        // Arrange
        $mockRepo = Mockery::mock(UserRepositoryInterface::class);

        // Expected data returned from repository
        $distributionData = [
            ['region' => 'Sudeste', 'count' => 45],
            ['region' => 'Nordeste', 'count' => 25],
            ['region' => 'Sul', 'count' => 15],
            ['region' => 'Centro-Oeste', 'count' => 10],
            ['region' => 'Norte', 'count' => 5]
        ];

        // Expect getDistributionByRegion to be called
        $mockRepo->shouldReceive('getDistributionByRegion')
            ->once()
            ->andReturn($distributionData);

        $this->app->instance(UserRepositoryInterface::class, $mockRepo);

        $useCase = new GetUserDistributionDataUseCase($mockRepo);

        // Act
        $result = $useCase->execute('30d');

        // Assert
        $this->assertInstanceOf(UserDistributionOutputDTO::class, $result);

        // Check the expected output
        $expectedLabels = ['Sudeste', 'Nordeste', 'Sul', 'Centro-Oeste', 'Norte'];
        $expectedData = [45, 25, 15, 10, 5];

        $this->assertEquals($expectedLabels, $result->labels);
        $this->assertEquals($expectedData, $result->datasets[0]['data']);

        // Check that we have proper styling attributes
        $this->assertArrayHasKey('backgroundColor', $result->datasets[0]);
        $this->assertArrayHasKey('borderWidth', $result->datasets[0]);
        $this->assertArrayHasKey('borderColor', $result->datasets[0]);
        $this->assertArrayHasKey('hoverOffset', $result->datasets[0]);
    }
}
