<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\ProductRatingFilterDTO;
use App\DTOs\Dashboard\Output\ProductRatingOutputDTO;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

class GetProductRatingDataUseCase
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(?ProductRepositoryInterface $productRepository = null)
    {
        $this->productRepository = $productRepository ?? new ProductRepository();
    }

    public function execute(?ProductRatingFilterDTO $filter): ProductRatingOutputDTO
    {
        // Atributos para avaliação
        $attributes = ['Qualidade', 'Preço', 'Usabilidade', 'Design', 'Suporte', 'Funcionalidades'];

        if ($filter) {
            $ratings = $this->productRepository->getFilteredProductRatings($filter);
            $currentProductRatings = $ratings['product'];
            $competitorRatings = $ratings['competitor'];

            // Usar atributos personalizados se fornecidos
            if ($filter->attributes) {
                $attributes = $filter->attributes;
            }
        } else {
            // Dados do produto atual (média de avaliações)
            $currentProductRatings = $this->productRepository->getProductRatings(1);

            // Dados do produto concorrente (média de avaliações)
            $competitorRatings = $this->productRepository->getProductRatings(2);
        }

        // Convert array to associative array with string keys
        $labelArray = [];
        foreach ($attributes as $index => $attribute) {
            $labelArray["attribute_$index"] = $attribute;
        }

        $datasetsArray = [
            'currentProduct' => [
                'label' => 'Produto Atual',
                'data' => [
                    $currentProductRatings->quality ?? 0,
                    $currentProductRatings->price ?? 0,
                    $currentProductRatings->usability ?? 0,
                    $currentProductRatings->design ?? 0,
                    $currentProductRatings->support ?? 0,
                    $currentProductRatings->features ?? 0
                ],
                'borderColor' => 'rgba(99, 102, 241, 0.8)',
                'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                'borderWidth' => 2,
                'pointBackgroundColor' => 'rgba(99, 102, 241, 1)',
                'pointRadius' => 3
            ],
            'competitor' => [
                'label' => 'Concorrente',
                'data' => [
                    $competitorRatings->quality ?? 0,
                    $competitorRatings->price ?? 0,
                    $competitorRatings->usability ?? 0,
                    $competitorRatings->design ?? 0,
                    $competitorRatings->support ?? 0,
                    $competitorRatings->features ?? 0
                ],
                'borderColor' => 'rgba(236, 72, 153, 0.8)',
                'backgroundColor' => 'rgba(236, 72, 153, 0.1)',
                'borderWidth' => 2,
                'pointBackgroundColor' => 'rgba(236, 72, 153, 1)',
                'pointRadius' => 3
            ]
        ];

        return new ProductRatingOutputDTO(
            $labelArray,
            $datasetsArray
        );
    }
}
