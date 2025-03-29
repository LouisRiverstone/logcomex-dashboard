<?php

namespace App\Repositories;

use App\DTOs\Dashboard\ProductRatingFilterDTO;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get the product ratings
     *
     * @param int $productId
     * @return object|null
     * @throws InvalidArgumentException
     */
    public function getProductRatings(int $productId): object|null
    {
        return DB::table('product_ratings')
            ->where('product_id', $productId)
            ->select(
                DB::raw('AVG(quality) as quality'),
                DB::raw('AVG(price) as price'),
                DB::raw('AVG(usability) as usability'),
                DB::raw('AVG(design) as design'),
                DB::raw('AVG(support) as support'),
                DB::raw('AVG(features) as features')
            )
            ->first();
    }

    /**
     * Get the filtered product ratings
     *
     * @param ProductRatingFilterDTO $filter
     * @return array<string,object|null>
     */
    public function getFilteredProductRatings(ProductRatingFilterDTO $filter): array
    {
        $productRatings = DB::table('product_ratings')
            ->where('product_id', $filter->productId)
            ->select(
                DB::raw('AVG(quality) as quality'),
                DB::raw('AVG(price) as price'),
                DB::raw('AVG(usability) as usability'),
                DB::raw('AVG(design) as design'),
                DB::raw('AVG(support) as support'),
                DB::raw('AVG(features) as features')
            )
            ->first();

        $competitorRatings = DB::table('product_ratings')
            ->where('product_id', $filter->competitorId)
            ->select(
                DB::raw('AVG(quality) as quality'),
                DB::raw('AVG(price) as price'),
                DB::raw('AVG(usability) as usability'),
                DB::raw('AVG(design) as design'),
                DB::raw('AVG(support) as support'),
                DB::raw('AVG(features) as features')
            )
            ->first();

        return [
            'product' => $productRatings,
            'competitor' => $competitorRatings
        ];
    }
}
