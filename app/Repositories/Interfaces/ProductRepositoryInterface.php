<?php

namespace App\Repositories\Interfaces;

use App\DTOs\Dashboard\ProductRatingFilterDTO;

interface ProductRepositoryInterface
{
    /**
     * Get the product ratings
     *
     * @param int $productId
     * @return object|null
     */
    public function getProductRatings(int $productId): object|null;

    /**
     * Get the filtered product ratings
     *
     * @param ProductRatingFilterDTO $filter
     * @return array<string,object|null>
     */
    public function getFilteredProductRatings(ProductRatingFilterDTO $filter): array;
}
