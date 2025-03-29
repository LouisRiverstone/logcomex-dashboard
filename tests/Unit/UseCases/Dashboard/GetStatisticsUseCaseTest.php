<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\StatisticsOutputDTO;
use App\Repositories\Interfaces\StatisticsRepositoryInterface;
use App\UseCases\Dashboard\GetStatisticsUseCase;
use Tests\TestCase;
use Mockery;
use Carbon\Carbon;

class GetStatisticsUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecute()
    {
        // Arrange
        $mockRepo = Mockery::mock(StatisticsRepositoryInterface::class);

        // Usuários ativos
        $mockRepo->shouldReceive('getActiveUsers')
            ->with(Mockery::type(Carbon::class))
            ->andReturn(1000);
        $mockRepo->shouldReceive('getActiveUsers')
            ->with(Mockery::type(Carbon::class))
            ->andReturn(1000);

        // Vendas
        $mockRepo->shouldReceive('getNewSales')
            ->with(Mockery::type(Carbon::class))
            ->andReturn(5000);
        $mockRepo->shouldReceive('getNewSales')
            ->with(Mockery::type(Carbon::class))
            ->andReturn(4500);

        // Receita
        $mockRepo->shouldReceive('getTotalRevenue')
            ->with(Mockery::type(Carbon::class))
            ->andReturn(50000);
        $mockRepo->shouldReceive('getTotalRevenue')
            ->with(Mockery::type(Carbon::class))
            ->andReturn(45000);

        // Taxa de conversão
        $mockRepo->shouldReceive('getConversionRate')
            ->with(Mockery::type(Carbon::class))
            ->andReturn(3.5);
        $mockRepo->shouldReceive('getConversionRate')
            ->with(Mockery::type(Carbon::class))
            ->andReturn(3.0);

        // Mock trends
        $mockRepo->shouldReceive('getUserTrend')
            ->once()
            ->andReturn([800, 850, 900, 950, 1000]);

        $mockRepo->shouldReceive('getSalesTrend')
            ->once()
            ->andReturn([4500, 4600, 4700, 4800, 5000]);

        $mockRepo->shouldReceive('getRevenueTrend')
            ->once()
            ->andReturn([45000, 46000, 47000, 48000, 50000]);

        $mockRepo->shouldReceive('getConversionTrend')
            ->once()
            ->andReturn([3.0, 3.1, 3.2, 3.3, 3.5]);

        $this->app->instance(StatisticsRepositoryInterface::class, $mockRepo);

        $useCase = new GetStatisticsUseCase($mockRepo);

        // Act
        $result = $useCase->execute();

        // Assert
        $this->assertInstanceOf(StatisticsOutputDTO::class, $result);

        // Check that we have 4 statistic cards
        $this->assertCount(4, $result->statistics);

        // Check structure and values of statistic cards
        $this->assertEquals('Usuários Ativos', $result->statistics[0]['title']);
        $this->assertEquals('1.000', $result->statistics[0]['value']);
        $this->assertEquals('+0%', $result->statistics[0]['change']['value']);
        $this->assertEquals('success', $result->statistics[0]['change']['type']);
        $this->assertEquals([800, 850, 900, 950, 1000], $result->statistics[0]['sparklineData']);

        $this->assertEquals('Novas Vendas', $result->statistics[1]['title']);
        $this->assertEquals('R$ 5.000,00', $result->statistics[1]['value']);
        $this->assertEquals('+0%', $result->statistics[1]['change']['value']);
        $this->assertEquals('success', $result->statistics[1]['change']['type']);

        $this->assertEquals('Receita Total', $result->statistics[2]['title']);
        $this->assertEquals('R$ 50.000,00', $result->statistics[2]['value']);
        $this->assertEquals('+0%', $result->statistics[2]['change']['value']);
        $this->assertEquals('success', $result->statistics[2]['change']['type']);

        $this->assertEquals('Taxa de Conversão', $result->statistics[3]['title']);
        $this->assertEquals('3.5%', $result->statistics[3]['value']);
        $this->assertEquals('+0%', $result->statistics[3]['change']['value']);
        $this->assertEquals('success', $result->statistics[3]['change']['type']);
    }
}
