<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\DTOs\Dashboard\Output\TransactionsOutputDTO;
use App\DTOs\Dashboard\TransactionFilterDTO;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\UseCases\Dashboard\GetTransactionsUseCase;
use Tests\TestCase;
use Mockery;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class GetTransactionsUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecuteWithoutFilter()
    {
        // Arrange
        $mockRepo = Mockery::mock(TransactionRepositoryInterface::class);

        // Sample transactions data
        $transactionsData = [
            [
                'id' => 1,
                'user' => [
                    'id' => 1,
                    'name' => 'João Silva',
                    'avatar' => 'avatar1.jpg'
                ],
                'amount' => 1500.00,
                'type' => 'sale',
                'status' => 'completed',
                'created_at' => '2023-04-10 14:30:00'
            ],
            [
                'id' => 2,
                'user' => [
                    'id' => 2,
                    'name' => 'Maria Oliveira',
                    'avatar' => 'avatar2.jpg'
                ],
                'amount' => 750.50,
                'type' => 'refund',
                'status' => 'pending',
                'created_at' => '2023-04-11 09:15:00'
            ]
        ];

        // Create paginator from transactions data
        $paginator = new LengthAwarePaginator(
            $transactionsData,
            count($transactionsData),
            10,
            1
        );

        // Expect getFilteredTransactions to be called with null or empty filter
        $mockRepo->shouldReceive('getFilteredTransactions')
            ->with(Mockery::type(TransactionFilterDTO::class))
            ->once()
            ->andReturn($paginator);

        $this->app->instance(TransactionRepositoryInterface::class, $mockRepo);

        $useCase = new GetTransactionsUseCase($mockRepo);

        // Act
        $result = $useCase->execute(null);

        // Assert
        $this->assertInstanceOf(TransactionsOutputDTO::class, $result);

        // Check the structure of the formatted transactions
        $this->assertCount(2, $result->data);
        $this->assertEquals(1, $result->data[0]['id']);
        $this->assertEquals('João Silva', $result->data[0]['user']['name']);
        $this->assertEquals(1500.00, $result->data[0]['amount']);
        $this->assertEquals('sale', $result->data[0]['type']);
        $this->assertEquals('completed', $result->data[0]['status']);

        $this->assertEquals(2, $result->data[1]['id']);
        $this->assertEquals('Maria Oliveira', $result->data[1]['user']['name']);
        $this->assertEquals(750.50, $result->data[1]['amount']);
        $this->assertEquals('refund', $result->data[1]['type']);
        $this->assertEquals('pending', $result->data[1]['status']);
    }

    public function testExecuteWithFilter()
    {
        // Arrange
        $mockRepo = Mockery::mock(TransactionRepositoryInterface::class);

        // Create a filter com os tipos corretos
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        $filter = new TransactionFilterDTO(
            type: 'sale',
            status: 'completed',
            startDate: $startDate,
            endDate: $endDate
        );

        // Sample filtered transactions
        $filteredTransactions = [
            [
                'id' => 1,
                'user' => [
                    'id' => 1,
                    'name' => 'João Silva',
                    'avatar' => 'avatar1.jpg'
                ],
                'amount' => 1500.00,
                'type' => 'sale',
                'status' => 'completed',
                'created_at' => '2023-04-10 14:30:00'
            ]
        ];

        // Expect getFilteredTransactions to be called with filter
        $paginator = new LengthAwarePaginator(
            $filteredTransactions,
            count($filteredTransactions),
            10,
            1
        );

        $mockRepo->shouldReceive('getFilteredTransactions')
            ->with(Mockery::type(TransactionFilterDTO::class))
            ->once()
            ->andReturn($paginator);

        $this->app->instance(TransactionRepositoryInterface::class, $mockRepo);

        $useCase = new GetTransactionsUseCase($mockRepo);

        // Act
        $result = $useCase->execute($filter);

        // Assert
        $this->assertInstanceOf(TransactionsOutputDTO::class, $result);

        // Check the filtered transactions
        $this->assertCount(1, $result->data);
        $this->assertEquals(1, $result->data[0]['id']);
        $this->assertEquals('João Silva', $result->data[0]['user']['name']);
        $this->assertEquals('sale', $result->data[0]['type']);
        $this->assertEquals('completed', $result->data[0]['status']);
    }

    public function testExecuteWithPagination()
    {
        // Arrange
        $mockRepo = Mockery::mock(TransactionRepositoryInterface::class);

        // Sample transactions data
        $transactionsData = [
            [
                'id' => 1,
                'user' => [
                    'id' => 1,
                    'name' => 'João Silva',
                    'avatar' => 'avatar1.jpg'
                ],
                'amount' => 1500.00,
                'type' => 'sale',
                'status' => 'completed',
                'created_at' => '2023-04-10 14:30:00'
            ],
            [
                'id' => 2,
                'user' => [
                    'id' => 2,
                    'name' => 'Maria Oliveira',
                    'avatar' => 'avatar2.jpg'
                ],
                'amount' => 750.50,
                'type' => 'refund',
                'status' => 'pending',
                'created_at' => '2023-04-11 09:15:00'
            ]
        ];

        // Expect getFilteredTransactions to be called
        $paginator = new LengthAwarePaginator(
            $transactionsData,
            count($transactionsData),
            10,
            1
        );

        $mockRepo->shouldReceive('getFilteredTransactions')
            ->with(Mockery::type(TransactionFilterDTO::class))
            ->once()
            ->andReturn($paginator);

        $this->app->instance(TransactionRepositoryInterface::class, $mockRepo);

        $useCase = new GetTransactionsUseCase($mockRepo);

        // Act
        $result = $useCase->execute(null);

        // Assert
        $this->assertInstanceOf(TransactionsOutputDTO::class, $result);

        // Verificar se data existe e tem o formato esperado
        $this->assertNotEmpty($result->data, "A propriedade data não deve estar vazia");
        $this->assertCount(2, $result->data);

        // Verificar alguns dados básicos
        $this->assertEquals(1, $result->data[0]['id']);
        $this->assertEquals('João Silva', $result->data[0]['user']['name']);

        // Verificar metadados
        $this->assertArrayHasKey('total', $result->meta);
        $this->assertArrayHasKey('per_page', $result->meta);
        $this->assertArrayHasKey('current_page', $result->meta);
    }
}
