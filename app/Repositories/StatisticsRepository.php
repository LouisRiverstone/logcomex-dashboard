<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\User;
use App\Models\Visitor;
use App\Models\Revenue;
use App\Repositories\Interfaces\StatisticsRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsRepository implements StatisticsRepositoryInterface
{
    /**
     * Get the active users
     *
     * @param Carbon $since
     * @return int
     */
    public function getActiveUsers(Carbon $since): int
    {
        return User::where('last_active_at', '>=', $since)->count();
    }

    /**
     * Get the new sales
     *
     * @param Carbon $since
     * @return float
     */
    public function getNewSales(Carbon $since): float
    {
        return (float) Sale::where('created_at', '>=', $since)->sum('amount');
    }

    /**
     * Get the total revenue
     *
     * @param Carbon $since
     * @return float
     */
    public function getTotalRevenue(Carbon $since): float
    {
        Log::debug('StatisticsRepository: Getting total revenue since ' . $since);

        $query = Revenue::where('date', '>=', $since);
        Log::debug('StatisticsRepository: SQL query: ' . $query->toSql());
        Log::debug('StatisticsRepository: SQL bindings: ' . json_encode($query->getBindings()));

        $total = $query->sum('amount');
        Log::debug('StatisticsRepository: Total revenue result: ' . $total);

        return (float) $total;
    }

    /**
     * Get the conversion rate
     *
     * @param Carbon $since
     * @return float
     */
    public function getConversionRate(Carbon $since): float
    {
        $totalVisitors = Visitor::where('visited_at', '>=', $since)->count();
        $totalConversions = Sale::where('created_at', '>=', $since)->count();

        return $totalVisitors > 0 ? round(($totalConversions / $totalVisitors) * 100, 2) : 0;
    }

    /**
     * Get the user trend
     *
     * @param int $days
     * @return array<string,mixed>
     */
    public function getUserTrend(int $days = 30): array
    {
        return User::where('last_active_at', '>=', Carbon::now()->subDays($days))
            ->groupBy(DB::raw('DATE(last_active_at)'))
            ->orderBy('date')
            ->select(DB::raw('DATE(last_active_at) as date, COUNT(*) as count'))
            ->get()
            ->pluck('count')
            ->toArray();
    }

    /**
     * Get the sales trend
     *
     * @param int $days
     * @return array<float,int>
     */
    public function getSalesTrend(int $days = 30): array
    {
        return Sale::where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->select(DB::raw('DATE(created_at) as date, SUM(amount) as total'))
            ->get()
            ->pluck('total')
            ->toArray();
    }

    /**
     * Get the revenue trend
     *
     * @param int $days
     * @return array<float,int>
     */
    public function getRevenueTrend(int $days = 30): array
    {
        return Revenue::where('date', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->select('date', DB::raw('SUM(amount) as total'))
            ->get()
            ->pluck('total')
            ->toArray();
    }

    /**
     * Get the conversion trend
     *
     * @param int $days
     * @return list<float>
     */
    public function getConversionTrend(int $days = 30): array
    {
        $trend = [];

        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayVisitors = Visitor::whereDate('visited_at', $date)->count();
            $dayConversions = Sale::whereDate('created_at', $date)->count();
            $dayRate = $dayVisitors > 0 ? round(($dayConversions / $dayVisitors) * 100, 2) : 0;
            $trend[] = (float) $dayRate;
        }

        return $trend;
    }
}
