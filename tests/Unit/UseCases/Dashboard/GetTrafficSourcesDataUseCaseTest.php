<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\TrafficSourcesOutputDTO;
use App\Repositories\Interfaces\VisitorsRepositoryInterface;
use App\UseCases\Dashboard\GetTrafficSourcesDataUseCase;
use Tests\TestCase;
use Mockery;

class GetTrafficSourcesDataUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute()
    {
        // Arrange
        $mockRepo = Mockery::mock(VisitorsRepositoryInterface::class);

        // Expected data returned from repository
        $sourcesData = [
            ['source' => 'Direct', 'count' => 35],
            ['source' => 'Organic Search', 'count' => 25],
            ['source' => 'Social Media', 'count' => 20],
            ['source' => 'Referral', 'count' => 15],
            ['source' => 'Email', 'count' => 5]
        ];

        // Expect getVisitorsBySource to be called
        $mockRepo->shouldReceive('getVisitorsBySource')
            ->once()
            ->andReturn($sourcesData);

        $this->app->instance(VisitorsRepositoryInterface::class, $mockRepo);

        $useCase = new GetTrafficSourcesDataUseCase($mockRepo);

        // Act
        $result = $useCase->execute('30d');

        // Assert
        $this->assertInstanceOf(TrafficSourcesOutputDTO::class, $result);

        // Check the expected output
        $expectedLabels = ['Direct', 'Organic Search', 'Social Media', 'Referral', 'Email'];
        $expectedData = [35, 25, 20, 15, 5];

        $this->assertEquals($expectedLabels, $result->labels);
        $this->assertEquals($expectedData, $result->datasets[0]['data']);

        // Check styling attributes
        $this->assertArrayHasKey('backgroundColor', $result->datasets[0]);
        $this->assertArrayHasKey('borderWidth', $result->datasets[0]);
        $this->assertArrayHasKey('borderColor', $result->datasets[0]);
        $this->assertArrayHasKey('hoverOffset', $result->datasets[0]);
    }
}
