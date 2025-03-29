<?php

namespace Tests\Feature\Http\Controllers;

use App\UseCases\Dashboard\GetStatisticsUseCase;
use App\UseCases\Dashboard\GetVisitorsDataUseCase;
use App\UseCases\Dashboard\GetTasksUseCase;
use App\UseCases\Dashboard\GetTransactionsUseCase;
use App\DTOs\Dashboard\Output\StatisticsOutputDTO;
use App\DTOs\Dashboard\Output\VisitorsDataOutputDTO;
use App\DTOs\Dashboard\Output\TasksOutputDTO;
use App\DTOs\Dashboard\Output\TransactionsOutputDTO;
use App\DTOs\Dashboard\VisitorsFilterDTO;
use App\DTOs\Dashboard\TaskFilterDTO;
use App\DTOs\Dashboard\TransactionFilterDTO;
use Mockery;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetStatistics()
    {
        // Sample data
        $mockData = [
            [
                'title' => 'Usuários Ativos',
                'value' => '1.000',
                'change' => [
                    'value' => '+10.5%',
                    'type' => 'success'
                ],
                'period' => 'Desde o último mês',
                'sparklineData' => [100, 120, 140, 130, 150]
            ],
            [
                'title' => 'Novas Vendas',
                'value' => 'R$ 5.000,00',
                'change' => [
                    'value' => '+5.2%',
                    'type' => 'success'
                ],
                'period' => 'Desde a semana passada',
                'sparklineData' => [500, 550, 600, 650, 700]
            ],
            [
                'title' => 'Receita Total',
                'value' => 'R$ 50.000,00',
                'change' => [
                    'value' => '+8.1%',
                    'type' => 'success'
                ],
                'period' => 'Desde o último mês',
                'sparklineData' => [5000, 5500, 6000, 6500, 7000]
            ],
            [
                'title' => 'Taxa de Conversão',
                'value' => '3.5%',
                'change' => [
                    'value' => '+0.5%',
                    'type' => 'success'
                ],
                'period' => 'Desde ontem',
                'sparklineData' => [2.5, 2.8, 3.0, 3.2, 3.5]
            ]
        ];

        // Create mock DTO
        $mockDTO = new StatisticsOutputDTO($mockData);

        // Create mock use case
        $mockUseCase = Mockery::mock(GetStatisticsUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->once()
            ->andReturn($mockDTO);

        // Register the mock with the container
        $this->app->instance(GetStatisticsUseCase::class, $mockUseCase);

        // Make the API call
        $response = $this->getJson('/api/v1/dashboard/statistics');

        // Assert the response
        $response->assertStatus(200)
                ->assertJson($mockData);
    }

    public function testGetVisitorsData()
    {
        // Sample data for visitors
        $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $datasets = [
            [
                'label' => 'Visitantes 2023',
                'data' => [100, 150, 200, 250, 300, 350, 400, 450, 500, 550, 600, 650],
                'borderColor' => '#6366F1',
                'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                'tension' => 0.4,
                'fill' => true
            ],
            [
                'label' => 'Visitantes 2022',
                'data' => [50, 75, 100, 125, 150, 175, 200, 225, 250, 275, 300, 325],
                'borderColor' => '#94A3B8',
                'backgroundColor' => 'transparent',
                'tension' => 0.4,
                'borderDash' => [5, 5]
            ]
        ];

        // Create mock DTO
        $mockDTO = new VisitorsDataOutputDTO($labels, $datasets);

        // Create mock use case
        $mockUseCase = Mockery::mock(GetVisitorsDataUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->once()
            ->withArgs(function ($arg) {
                // Accept any string or VisitorsFilterDTO argument
                return is_string($arg) || $arg instanceof VisitorsFilterDTO;
            })
            ->andReturn($mockDTO);

        // Register the mock with the container
        $this->app->instance(GetVisitorsDataUseCase::class, $mockUseCase);

        // Make the API call
        $response = $this->getJson('/api/v1/dashboard/visitors');

        // Assert the response
        $response->assertStatus(200)
                ->assertJson([
                    'labels' => $labels,
                    'datasets' => $datasets
                ]);
    }

    public function testGetVisitorsDataWithFilters()
    {
        // Sample data for visitors with filters
        $labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $datasets = [
            [
                'label' => 'Visitantes 2023',
                'data' => [30, 40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140],
                'borderColor' => '#6366F1',
                'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                'tension' => 0.4,
                'fill' => true
            ],
            [
                'label' => 'Visitantes 2022',
                'data' => [15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70],
                'borderColor' => '#94A3B8',
                'backgroundColor' => 'transparent',
                'tension' => 0.4,
                'borderDash' => [5, 5]
            ]
        ];

        // Create mock DTO
        $mockDTO = new VisitorsDataOutputDTO($labels, $datasets);

        // Create mock use case
        $mockUseCase = Mockery::mock(GetVisitorsDataUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->once()
            ->withArgs(function ($arg) {
                // Accept any string or VisitorsFilterDTO argument
                return is_string($arg) || $arg instanceof VisitorsFilterDTO;
            })
            ->andReturn($mockDTO);

        // Register the mock with the container
        $this->app->instance(GetVisitorsDataUseCase::class, $mockUseCase);

        // Make the API call with filter params
        $response = $this->getJson('/api/v1/dashboard/visitors?source=direct&region=US&period=90d');

        // Assert the response
        $response->assertStatus(200)
                ->assertJson([
                    'labels' => $labels,
                    'datasets' => $datasets
                ]);
    }

    public function testGetTasks()
    {
        // Sample tasks data
        $tasks = [
            [
                'id' => 1,
                'title' => 'Revisar relatório mensal',
                'description' => 'Revisar o relatório mensal de vendas e fazer comentários',
                'due_date' => '2023-04-15',
                'priority' => 'high',
                'completed' => false,
                'assignee' => [
                    'id' => 1,
                    'name' => 'João Silva',
                    'avatar' => 'avatar1.jpg'
                ]
            ],
            [
                'id' => 2,
                'title' => 'Atualizar documentação',
                'description' => 'Atualizar a documentação do produto com os novos recursos',
                'due_date' => '2023-04-20',
                'priority' => 'medium',
                'completed' => false,
                'assignee' => [
                    'id' => 2,
                    'name' => 'Maria Oliveira',
                    'avatar' => 'avatar2.jpg'
                ]
            ]
        ];

        // Pagination metadata
        $pagination = [
            'current_page' => 1,
            'total' => 2,
            'per_page' => 10,
            'last_page' => 1
        ];

        // Mock DTO with pagination
        $mockDTO = new TasksOutputDTO($tasks, $pagination);

        // Create mock use case
        $mockUseCase = Mockery::mock(GetTasksUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->once()
            ->withArgs(function ($arg) {
                // Accept any TaskFilterDTO argument
                return $arg instanceof TaskFilterDTO;
            })
            ->andReturn($mockDTO);

        // Register the mock with the container
        $this->app->instance(GetTasksUseCase::class, $mockUseCase);

        // Make the API call
        $response = $this->getJson('/api/v1/dashboard/tasks');

        // Assert the response
        $response->assertStatus(200)
                ->assertJson([
                    'data' => $tasks,
                    'meta' => $pagination
                ]);
    }

    public function testGetTransactions()
    {
        // Sample transactions data
        $transactions = [
            [
                'id' => 1,
                'user' => [
                    'id' => 1,
                    'name' => 'João Silva',
                    'avatar' => 'avatar1.jpg'
                ],
                'amount' => 1500.00,
                'type' => 'deposit',
                'status' => 'completed',
                'date' => '2023-04-10 14:30:00'
            ],
            [
                'id' => 2,
                'user' => [
                    'id' => 2,
                    'name' => 'Maria Oliveira',
                    'avatar' => 'avatar2.jpg'
                ],
                'amount' => 750.50,
                'type' => 'withdrawal',
                'status' => 'pending',
                'date' => '2023-04-11 09:15:00'
            ]
        ];

        // Pagination metadata
        $pagination = [
            'current_page' => 1,
            'total' => 2,
            'per_page' => 10,
            'last_page' => 1
        ];

        // Mock DTO with pagination
        $mockDTO = new TransactionsOutputDTO($transactions, $pagination);

        // Create mock use case
        $mockUseCase = Mockery::mock(GetTransactionsUseCase::class);
        $mockUseCase->shouldReceive('execute')
            ->once()
            ->withArgs(function ($arg) {
                // Accept any TransactionFilterDTO argument
                return $arg instanceof TransactionFilterDTO;
            })
            ->andReturn($mockDTO);

        // Register the mock with the container
        $this->app->instance(GetTransactionsUseCase::class, $mockUseCase);

        // Make the API call
        $response = $this->getJson('/api/v1/dashboard/transactions');

        // Assert the response
        $response->assertStatus(200)
                ->assertJson([
                    'data' => $transactions,
                    'meta' => $pagination
                ]);
    }
}
