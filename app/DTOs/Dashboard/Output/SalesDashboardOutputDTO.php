<?php

namespace App\DTOs\Dashboard\Output;

class SalesDashboardOutputDTO
{
    /**
     * @param array<int,object> $salesByRegion
     * @param array<int,object> $salesByUser
     * @param array<int,object> $salesByStatus
     * @param array<int,object> $salesByProduct
     * @param array<int,object> $salesByMonth
     */
    public function __construct(
        public readonly array $salesByRegion,
        public readonly array $salesByUser,
        public readonly array $salesByStatus,
        public readonly array $salesByProduct,
        public readonly array $salesByMonth
    ) {
    }

    /**
     * Create a new SalesDashboardOutputDTO from an array
     *
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['salesByRegion'] ?? [],
            $data['salesByUser'] ?? [],
            $data['salesByStatus'] ?? [],
            $data['salesByProduct'] ?? [],
            $data['salesByMonth'] ?? []
        );
    }

    /**
     * Convert the SalesDashboardOutputDTO to an array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return $this->makeGraphData();
    }

    /**
     * Make the graph data
     *
     * @return array<string,mixed>
     */
    private function makeGraphData(): array
    {
        $salesByRegionGraphData = [
            'labels' => array_column($this->salesByRegion, 'region'),
            'datasets' => [
                [
                    'label' => 'Vendas por Região',
                    'data' => array_column($this->salesByRegion, 'total'),
                    'backgroundColor' => $this->getColors(count($this->salesByRegion)),
                ],
            ],
        ];

        $salesByUserGraphData = [
            'labels' => array_column($this->salesByUser, 'user_name'),
            'datasets' => [
                [
                    'label' => 'Vendas por Usuário (Top 5)',
                    'data' => array_column($this->salesByUser, 'total'),
                    'backgroundColor' => $this->getColors(count($this->salesByUser)),
                ],
            ],
        ];

        $salesByStatusGraphData = [
            'labels' => array_column($this->salesByStatus, 'status'),
            'datasets' => [
                [
                    'label' => 'Vendas por Status',
                    'data' => array_column($this->salesByStatus, 'total'),
                    'backgroundColor' => [
                        'rgba(249, 115, 22, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(34, 197, 94, 0.7)'
                    ],
                ],
            ],
        ];

        $salesByProductGraphData = [
            'labels' => array_column($this->salesByProduct, 'product_name'),
            'datasets' => [
                [
                    'label' => 'Vendas por Produto',
                    'data' => array_column($this->salesByProduct, 'total'),
                    'backgroundColor' => $this->getColors(count($this->salesByProduct)),
                ],
            ],
        ];

        $salesByMonthGraphData = [
            'labels' => array_column($this->salesByMonth, 'created_at'),
            'datasets' => [
                [
                    'label' => 'Vendas por Mês',
                    'data' => array_column($this->salesByMonth, 'total'),
                    'backgroundColor' => $this->getColors(count($this->salesByMonth)),
                ],
            ],
        ];

        return [
            'salesByRegion' => $salesByRegionGraphData,
            'salesByUser' => $salesByUserGraphData,
            'salesByStatus' => $salesByStatusGraphData,
            'salesByProduct' => $salesByProductGraphData,
            'salesByMonth' => $salesByMonthGraphData,
        ];
    }

    /**
     * @param int $arraySize
     * @return array<int,string>
     */
    private function getColors(int $arraySize): array
    {
        $colors = [];

        for ($i = 0; $i < $arraySize; $i++) {
            $colors[] = 'rgba(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ', 0.7)';
        }

        return $colors;
    }
}
