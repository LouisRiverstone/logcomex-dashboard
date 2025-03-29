<?php

namespace Tests\Feature\Http\Controllers;

use App\UseCases\Dashboard\GetRevenueDataUseCase;
use App\UseCases\Dashboard\GetSalesByCategoryDataUseCase;
use App\DTOs\Dashboard\Output\RevenueDataOutputDTO;
use App\DTOs\Dashboard\Output\SalesByCategoryOutputDTO;
use App\DTOs\Dashboard\RevenueFilterDTO;
use Mockery;
use Tests\FeatureTestCase;

class DashboardControllerRevenueTest extends FeatureTestCase
{
    // Definir autoSeed como true para garantir que as migrações sejam executadas
    protected $autoSeed = true;

    public function testGetRevenueData()
    {
        // Sample revenue data
        $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $datasets = [
            [
                'label' => 'Receita 2023',
                'data' => [50000, 55000, 60000, 65000, 70000, 75000, 80000, 85000, 90000, 95000, 100000, 105000],
                'borderColor' => '#6366F1',
                'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                'tension' => 0.4,
                'fill' => true
            ]
        ];

        // Create mock DTO
        $mockDTO = new RevenueDataOutputDTO($labels, $datasets);

        // Create mock use case
        $mockUseCase = Mockery::mock(GetRevenueDataUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->once()
            ->withArgs(function ($arg) {
                // Accept any RevenueFilterDTO argument
                return $arg instanceof RevenueFilterDTO;
            })
            ->andReturn($mockDTO);

        // Register the mock with the container
        $this->app->instance(GetRevenueDataUseCase::class, $mockUseCase);

        // Make the API call
        $response = $this->getJson('/api/v1/dashboard/revenue');

        // Assert the response
        $response->assertStatus(200)
                ->assertJson([
                    'labels' => $labels,
                    'datasets' => $datasets
                ]);
    }

    public function testGetRevenueDataWithYearAndComparison()
    {
        // Sample revenue data with current and previous year
        $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $datasets = [
            [
                'label' => 'Receita 2023',
                'data' => [45000, 50000, 55000, 60000, 65000, 70000, 75000, 80000, 85000, 90000, 95000, 100000],
                'borderColor' => '#6366F1',
                'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                'tension' => 0.4,
                'fill' => true
            ],
            [
                'label' => 'Receita 2022',
                'data' => [40000, 45000, 50000, 55000, 60000, 65000, 70000, 75000, 80000, 85000, 90000, 95000],
                'borderColor' => '#94A3B8',
                'backgroundColor' => 'transparent',
                'tension' => 0.4,
                'borderDash' => [5, 5]
            ]
        ];

        // Create mock DTO
        $mockDTO = new RevenueDataOutputDTO($labels, $datasets);

        // Create mock use case
        $mockUseCase = Mockery::mock(GetRevenueDataUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->once()
            ->withArgs(function ($arg) {
                // Accept any RevenueFilterDTO argument
                return $arg instanceof RevenueFilterDTO;
            })
            ->andReturn($mockDTO);

        // Register the mock with the container
        $this->app->instance(GetRevenueDataUseCase::class, $mockUseCase);

        // Make the API call with filter params
        $response = $this->getJson('/api/v1/dashboard/revenue?year=2023&comparison=prevYear');

        // Assert the response
        $response->assertStatus(200)
                ->assertJson([
                    'labels' => $labels,
                    'datasets' => $datasets
                ]);
    }

    public function testGetSalesByCategoryData()
    {
        // Sample sales by category data
        $labels = ['Eletrônicos', 'Vestuário', 'Alimentos', 'Móveis', 'Outros'];
        $datasets = [
            [
                'label' => 'Vendas por Categoria',
                'data' => [35, 25, 20, 15, 5],
                'backgroundColor' => ['#6366F1', '#F59E0B', '#10B981', '#EF4444', '#8B5CF6']
            ]
        ];

        // Create mock DTO
        $mockDTO = new SalesByCategoryOutputDTO($labels, $datasets);

        // Create mock use case
        $mockUseCase = Mockery::mock(GetSalesByCategoryDataUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->once()
            ->withAnyArgs()
            ->andReturn($mockDTO);

        // Register the mock with the container
        $this->app->instance(GetSalesByCategoryDataUseCase::class, $mockUseCase);

        // Make the API call
        $response = $this->getJson('/api/v1/dashboard/sales-by-category');

        // Assert the response
        $response->assertStatus(200)
                ->assertJson([
                    'labels' => $labels,
                    'datasets' => $datasets
                ]);
    }

    public function testGetSalesByCategoryDataWithPeriod()
    {
        // Sample sales by category data for specific period
        $labels = ['Eletrônicos', 'Vestuário', 'Alimentos', 'Móveis', 'Outros'];
        $datasets = [
            [
                'label' => 'Vendas por Categoria',
                'data' => [30, 20, 25, 20, 5],
                'backgroundColor' => ['#6366F1', '#F59E0B', '#10B981', '#EF4444', '#8B5CF6']
            ]
        ];

        // Create mock DTO
        $mockDTO = new SalesByCategoryOutputDTO($labels, $datasets);

        // Create mock use case
        $mockUseCase = Mockery::mock(GetSalesByCategoryDataUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->once()
            ->withArgs(function ($arg) {
                // Accept any string period argument
                return is_string($arg) && $arg === '90d';
            })
            ->andReturn($mockDTO);

        // Register the mock with the container
        $this->app->instance(GetSalesByCategoryDataUseCase::class, $mockUseCase);

        // Make the API call with period parameter
        $response = $this->getJson('/api/v1/dashboard/sales-by-category?period=90d');

        // Assert the response
        $response->assertStatus(200)
                ->assertJson([
                    'labels' => $labels,
                    'datasets' => $datasets
                ]);
    }
}
