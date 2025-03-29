<?php

namespace App\Repositories;

use App\Models\Revenue;
use App\Repositories\Interfaces\RevenueRepositoryInterface;
use App\DTOs\Dashboard\RevenueFilterDTO;
use Illuminate\Support\Facades\DB;

class RevenueRepository implements RevenueRepositoryInterface
{
    /**
     * Obtém os dados de receita mensal por tipo
     *
     * @param string $period
     * @param string|null $dateStart
     * @param string|null $dateEnd
     * @return array<string,mixed>
     */
    public function getMonthlyRevenueByType(string $period, ?string $dateStart = null, ?string $dateEnd = null): array
    {
        if (!$dateStart && !$dateEnd && $period !== 'custom') {
            //@phpstan-ignore-next-line
            $date = now()->period($period ?? '30d');

            $dateStart = $date->startDate->toDateString();
            $dateEnd = $date->endDate->toDateString();
        }

        if ($dateStart && !$dateEnd) {
            $dateEnd = $dateStart->toDateTimeString();
        }

        if (!$dateStart && $dateEnd) {
            $dateStart = $dateEnd->toDateTimeString();
        }

        $types = ['service', 'product', 'subscription'];
        $result = [];

        foreach ($types as $type) {
            $result[$type] = Revenue::where('type', $type)
                ->whereBetween('date', [$dateStart, $dateEnd])
                ->groupBy(DB::raw('MONTH(date)'))
                ->select(DB::raw('MONTH(date) as month, SUM(amount) as total'))
                ->get()
                ->keyBy('month')
                ->toArray();
        }

        return $result;
    }

    /**
     * Get the filtered revenue
     *
     * @param RevenueFilterDTO $filter
     * @return array<string,mixed>
     */
    public function getFilteredRevenue(RevenueFilterDTO $filter): array
    {
        $types = $filter->types ?? ['service', 'product', 'subscription'];
        $year = $filter->year ?? date('Y');
        $result = [];

        $query = Revenue::query();

        // Filtrar por data se especificado
        if ($filter->startDate && $filter->endDate) {
            $query->whereBetween('date', [$filter->startDate, $filter->endDate]);
        } else {
            $query->whereYear('date', $year);
        }

        // Se for para comparação, preparamos os dados para o período anterior também
        if ($filter->comparison === 'previous_year') {
            $previousYear = (int)$year - 1;
            $currentYearData = $this->getYearData($query->clone(), $types, $year);
            $previousYearData = $this->getYearData($query->clone(), $types, (string) $previousYear);

            return [
                'current' => $currentYearData,
                'previous' => $previousYearData
            ];
        }

        // Caso normal, apenas retornamos os dados filtrados
        foreach ($types as $type) {
            $result[$type] = $query->clone()
                ->where('type', $type)
                ->groupBy(DB::raw('MONTH(date)'))
                ->select(DB::raw('MONTH(date) as month, SUM(amount) as total'))
                ->get()
                ->keyBy('month')
                ->toArray();
        }

        return $result;
    }

    /**
     * Get the year data
     *
     * @param mixed $query
     * @param array<string> $types
     * @param string $year
     * @return array<string,mixed>
     */
    private function getYearData(mixed $query, array $types, string $year): array
    {
        $result = [];

        foreach ($types as $type) {
            $result[$type] = $query->clone()
                ->where('type', $type)
                ->whereYear('date', $year)
                ->groupBy(DB::raw('MONTH(date)'))
                ->select(DB::raw('MONTH(date) as month, SUM(amount) as total'))
                ->get()
                ->keyBy('month')
                ->toArray();
        }

        return $result;
    }
}
