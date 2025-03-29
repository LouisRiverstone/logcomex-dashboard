<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\TasksOutputDTO;
use App\DTOs\Dashboard\TaskFilterDTO;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\UseCases\Dashboard\GetTasksUseCase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Mockery;

class GetTasksUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecuteWithDefaultFilter()
    {
        // Arrange
        $mockRepo = Mockery::mock(TaskRepositoryInterface::class);

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

        // Set up mock method
        $mockRepo->shouldReceive('getPendingTasks')
            ->once()
            ->andReturn($tasks);

        $this->app->instance(TaskRepositoryInterface::class, $mockRepo);

        $useCase = new GetTasksUseCase($mockRepo);

        // Create empty filter (default)
        $filter = null;

        // Act
        $result = $useCase->execute($filter);

        // Assert
        $this->assertInstanceOf(TasksOutputDTO::class, $result);

        // Check the data
        $resultArray = $result->toArray();
        $this->assertIsArray($resultArray);

        // Verify that the formatter was applied to the tasks
        $this->assertArrayHasKey(0, $resultArray);
        $this->assertArrayHasKey('title', $resultArray[0]);
        $this->assertArrayHasKey('priority', $resultArray[0]);
        $this->assertArrayHasKey('completed', $resultArray[0]);
    }

    public function testExecuteWithCustomFilter()
    {
        // Arrange
        $mockRepo = Mockery::mock(TaskRepositoryInterface::class);

        // Create custom filter
        $filter = new TaskFilterDTO('high', false);

        // Sample filtered tasks
        $tasksCollection = collect([
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
            ]
        ]);

        // Create a paginator
        $paginatedTasks = new LengthAwarePaginator(
            $tasksCollection,
            1,  // total
            10, // per page
            1   // current page
        );

        // Set up mock method
        $mockRepo->shouldReceive('getFilteredTasks')
            ->once()
            ->andReturn($paginatedTasks);

        $this->app->instance(TaskRepositoryInterface::class, $mockRepo);

        $useCase = new GetTasksUseCase($mockRepo);

        // Act
        $result = $useCase->execute($filter);

        // Assert
        $this->assertInstanceOf(TasksOutputDTO::class, $result);

        // Check the result data
        $resultArray = $result->toArray();
        $this->assertIsArray($resultArray);

        // Verify it has the expected structure
        $this->assertArrayHasKey('data', $resultArray);
        $this->assertArrayHasKey('pagination', $resultArray);
    }
}
