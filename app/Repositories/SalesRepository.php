<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Repositories\Interfaces\SalesRepositoryInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use InvalidArgumentException;
use RuntimeException;

class SalesRepository implements SalesRepositoryInterface
{
    /**
     * Get sales by category
     * @param string $dateStart
     * @param int $limit
     * @return array<string,mixed>
     */
    public function getSalesByCategory(string $dateStart, int $limit = 5): array
    {
        return DB::table('categories')
            ->select([
                'categories.name',
                DB::raw('COUNT(DISTINCT sales.id) as total')
            ])
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereDate('sales.created_at', '>=', $dateStart)
            ->groupBy('categories.id', 'categories.name')
            ->get()
            ->toArray();
    }

    /**
     * Get sale details
     * @param int $saleId
     * @return object
     */
    public function viewSaleDetails(int $saleId): object
    {
        $sale = Sale::find($saleId);

        if (!$sale) {
            throw new \Exception('Sale not found');
        }

        $sale->load('items', 'user', 'items.product', 'items.product.category');

        return $sale;
    }


    /**
     * Get sales dashboard table
     * @param array<string,mixed> $filters
     * @return LengthAwarePaginator<array<string,mixed>>
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws BindingResolutionException
     */
    public function getSalesDashboardTable(array $filters = [
        'startDate' => null,
        'endDate' => null,
        'userName' => null,
        'productName' => null,
        'categoryId' => null,
        'region' => null,
        'status' => null,
        'page' => 1,
        'perPage' => 25,
        'orderBy' => 'created_at',
        'orderDirection' => 'desc',
    ]): LengthAwarePaginator
    {
        $query = DB::table('sales')
            ->select([
                'sales.id',
                'sales.amount',
                'sales.status',
                'users.name as user_name',
                'products.name as product_name',
                'users.region as region',
                'sale_items.quantity as quantity',
                'sales.created_at as created_at',
            ])
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id');

        if (!is_null($filters['startDate'])) {
            $query->whereDate('sales.created_at', '>=', $filters['startDate']);
        }

        if (!is_null($filters['endDate'])) {
            $query->whereDate('sales.created_at', '<=', $filters['endDate']);
        }

        if (!is_null($filters['userName'])) {
            $query->where('users.name', 'like', '%' . $filters['userName'] . '%');
        }

        if (!is_null($filters['productName'])) {
            $query->where('products.name', 'like', '%' . $filters['productName'] . '%');
        }

        if (!is_null($filters['categoryId'])) {
            $query->where('categories.id', $filters['categoryId']);
        }

        if (!is_null($filters['region'])) {
            $query->where('users.region', $filters['region']);
        }

        if (!is_null($filters['status'])) {
            $query->where('sales.status', $filters['status']);
        }

        if (!is_null($filters['orderBy'])) {
            $query->orderBy($filters['orderBy'], $filters['orderDirection'] ?? 'desc');
        }

        return $query->paginate(
            $filters['perPage'],
            ['*'],
            'page',
            $filters['page']
        );
    }

    /**
     * Get sales by region dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByRegionDashboardCharts(array $filters = [
        'startDate' => null,
        'endDate' => null,
        'region' => null
    ]): array
    {
        $query = DB::table('sales')
            ->select([
                'users.region as region',
                DB::raw('COUNT(DISTINCT sales.id) as total')
            ])
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->groupBy('users.region');

        if (isset($filters['startDate'])) {
            $query->whereDate('sales.created_at', '>=', $filters['startDate']);
        }

        if (isset($filters['endDate'])) {
            $query->whereDate('sales.created_at', '<=', $filters['endDate']);
        }

        if (isset($filters['region'])) {
            $query->where('users.region', $filters['region']);
        }

        return $query->get()->toArray();
    }

    /**
     * Get sales by user dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByUserDashboardCharts(array $filters = [
        'startDate' => null,
        'endDate' => null,
        'userName' => null,
    ]): array
    {
        $query = DB::table('sales')
            ->select([
                'users.name as user_name',
                DB::raw('SUM(sale_items.quantity * sale_items.price) as total'),
                DB::raw('RANK() OVER (ORDER BY SUM(sale_items.quantity * sale_items.price) DESC) as ranking'),
            ])
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->limit(5)
            ->orderBy('total', 'desc')
            ->groupBy('users.name');

        if (isset($filters['startDate'])) {
            $query->whereDate('sales.created_at', '>=', $filters['startDate']);
        }

        if (isset($filters['endDate'])) {
            $query->whereDate('sales.created_at', '<=', $filters['endDate']);
        }

        if (isset($filters['userName'])) {
            $query->where('users.name', 'like', '%' . $filters['userName'] . '%');
        }

        return $query->get()->toArray();
    }

    /**
     * Get sales by status dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByStatusDashboardCharts(array $filters = [
        'startDate' => null,
        'endDate' => null,
        'status' => null
    ]): array
    {
        $query = DB::table('sales')
            ->select([
                'sales.status',
                DB::raw('COUNT(sales.id) as total')
            ])
            ->groupBy('sales.status');

        if (isset($filters['startDate'])) {
            $query->whereDate('sales.created_at', '>=', $filters['startDate']);
        }

        if (isset($filters['endDate'])) {
            $query->whereDate('sales.created_at', '<=', $filters['endDate']);
        }

        if (isset($filters['status'])) {
            $query->where('sales.status', $filters['status']);
        }

        return $query->get()->toArray();
    }

    /**
     * Get sales by product dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByProductDashboardCharts(array $filters = [
        'startDate' => null,
        'endDate' => null,
        'productName' => null
    ]): array
    {
        $query = DB::table('sales')
            ->select([
                'products.name as product_name',
                DB::raw('COUNT(sales.amount) as total')
            ])
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->orderBy('total', 'desc')
            ->groupBy('products.name');

        if (isset($filters['startDate'])) {
            $query->whereDate('sales.created_at', '>=', $filters['startDate']);
        }

        if (isset($filters['endDate'])) {
            $query->whereDate('sales.created_at', '<=', $filters['endDate']);
        }

        if (isset($filters['productName'])) {
            $query->where('products.name', 'like', '%' . $filters['productName'] . '%');
        }

        return $query->get()->toArray();
    }

    /**
     * Get sales by category by months dashboard charts
     * @param array<string,mixed> $filters
     * @return array<string,mixed>
     */
    public function getSalesByCategoryByMouthsDashboardCharts(array $filters = [
        'startDate' => null,
        'endDate' => null,
        'categoryId' => null
    ]): array
    {
        $query = DB::table('sales')
            ->select([
                'categories.name as category_name',
                DB::raw('SUM(sale_items.quantity * sale_items.price) as total'),
                DB::raw('DATE_FORMAT(sales.created_at, "%Y-%m") as date')
            ])
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.name', 'date');

        if (isset($filters['startDate'])) {
            $query->whereDate('sales.created_at', '>=', $filters['startDate']);
        }

        if (isset($filters['endDate'])) {
            $query->whereDate('sales.created_at', '<=', $filters['endDate']);
        }

        if (isset($filters['categoryId'])) {
            $query->where('categories.id', $filters['categoryId']);
        }

        return $query->distinct()->get()->toArray();
    }
}
