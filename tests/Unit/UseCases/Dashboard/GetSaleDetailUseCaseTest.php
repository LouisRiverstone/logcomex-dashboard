<?php

namespace Tests\Unit\UseCases\Dashboard;

use App\Repositories\Interfaces\SalesRepositoryInterface;
use App\UseCases\Dashboard\GetSaleDetailUseCase;
use Tests\TestCase;
use Mockery;

class GetSaleDetailUseCaseTest extends TestCase
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

        // Sample sale details data
        $saleDetails = (object)[
            'id' => 123,
            'customer' => 'John Doe',
            'total' => 1500.00,
            'date' => '2023-05-15',
            'payment_method' => 'credit_card',
            'status' => 'completed',
            'items' => [
                [
                    'id' => 1,
                    'product' => 'Smartphone',
                    'quantity' => 1,
                    'price' => 1200.00,
                    'subtotal' => 1200.00
                ],
                [
                    'id' => 2,
                    'product' => 'Screen Protector',
                    'quantity' => 2,
                    'price' => 150.00,
                    'subtotal' => 300.00
                ]
            ]
        ];

        // Configure mock to return sale details
        $mockRepo->shouldReceive('viewSaleDetails')
            ->with(123)
            ->once()
            ->andReturn($saleDetails);

        $this->app->instance(SalesRepositoryInterface::class, $mockRepo);

        $useCase = new GetSaleDetailUseCase($mockRepo);

        // Act
        $result = $useCase->execute(123);

        // Assert
        $this->assertEquals($saleDetails, $result);
        $this->assertEquals(123, $result->id);
        $this->assertEquals('John Doe', $result->customer);
        $this->assertEquals(1500.00, $result->total);
        $this->assertEquals('2023-05-15', $result->date);
        $this->assertEquals('completed', $result->status);
        $this->assertCount(2, $result->items);
    }
}
