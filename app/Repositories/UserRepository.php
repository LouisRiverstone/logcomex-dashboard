<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get distribution by region
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array<string,mixed>
     */
    public function getDistributionByRegion(Carbon $startDate, Carbon $endDate): array
    {
        return User::select([
                'region',
                DB::raw('COUNT(*) as count')
            ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('region')
            ->get()
            ->toArray();
    }
}
