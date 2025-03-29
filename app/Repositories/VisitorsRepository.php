<?php

namespace App\Repositories;

use App\Models\Visitor;
use App\Repositories\Interfaces\VisitorsRepositoryInterface;
use App\DTOs\Dashboard\VisitorsFilterDTO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class VisitorsRepository implements VisitorsRepositoryInterface
{
    /**
     * Get the visitors by period
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array<string,mixed>
     */
    public function getVisitorsByPeriod(Carbon $startDate, Carbon $endDate): array
    {
        return Visitor::whereBetween('visited_at', [$startDate, $endDate])
            ->groupBy(DB::raw('MONTH(visited_at)'))
            ->select(DB::raw('MONTH(visited_at) as month, COUNT(*) as count'))
            ->get()
            ->keyBy('month')
            ->toArray();
    }

    /**
     * Get the visitors by source
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array<string,mixed>
     */
    public function getVisitorsBySource(Carbon $startDate, Carbon $endDate): array
    {
        return Visitor::select([
            'source',
            DB::raw('COUNT(*) as count')
        ])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('source')
            ->orderByDesc('count')
            ->get()
            ->toArray();
    }

    /**
     * Get the filtered visitors
     *
     * @param VisitorsFilterDTO $filter
     * @return array<string,mixed>|LengthAwarePaginator<Visitor>
     */
    public function getFilteredVisitors(VisitorsFilterDTO $filter): array|LengthAwarePaginator
    {
        $query = Visitor::query();

        // Definir o período com base no parâmetro
        if ($filter->startDate && $filter->endDate) {
            $query->whereBetween('visited_at', [$filter->startDate, $filter->endDate]);
        } elseif ($filter->period) {
            $days = match ($filter->period) {
                '7d' => 7,
                '90d' => 90,
                'year' => 365,
                default => 30
            };

            $startDate = Carbon::now()->subDays($days)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $query->whereBetween('visited_at', [$startDate, $endDate]);
        }

        // Filtros de fonte e região individuais
        if ($filter->source) {
            $query->where('source', $filter->source);
        }

        if ($filter->region) {
            $query->where('region', $filter->region);
        }

        // Filtros de fonte e região múltiplos (arrays)
        if ($filter->sources) {
            $query->whereIn('source', $filter->sources);
        }

        if ($filter->regions) {
            $query->whereIn('region', $filter->regions);
        }

        // Filtros adicionais (dispositivo e navegador)
        if ($filter->device) {
            $query->where('device', $filter->device);
        }

        if ($filter->devices) {
            $query->whereIn('device', $filter->devices);
        }

        if ($filter->browser) {
            $query->where('browser', $filter->browser);
        }

        if ($filter->browsers) {
            $query->whereIn('browser', $filter->browsers);
        }

        // Busca por termo
        if ($filter->searchTerm) {
            $query->where(function ($q) use ($filter) {
                $q->where('ip_address', 'like', '%' . $filter->searchTerm . '%')
                    ->orWhere('user_agent', 'like', '%' . $filter->searchTerm . '%')
                    ->orWhere('page', 'like', '%' . $filter->searchTerm . '%')
                    ->orWhere('referrer', 'like', '%' . $filter->searchTerm . '%');
            });
        }

        // Verificar se é um pedido de agrupamento por mês
        if ($filter->groupBy && in_array('month', $filter->groupBy)) {
            return $query->groupBy(DB::raw('MONTH(visited_at)'))
                ->select(DB::raw('MONTH(visited_at) as month, COUNT(*) as count'))
                ->orderBy('month')
                ->get()
                ->keyBy('month')
                ->toArray();
        }

        // Verificar outros tipos de agrupamento
        if ($filter->groupBy) {
            $groupResults = [];

            foreach ($filter->groupBy as $field) {
                if ($field === 'month') {
                    continue;
                } // Já tratado acima

                $groupQuery = clone $query;
                $results = $groupQuery->groupBy($field)
                    ->select($field, DB::raw('COUNT(*) as count'))
                    ->orderByDesc('count')
                    ->get()
                    ->toArray();

                $groupResults[$field] = $results;
            }

            if (!empty($groupResults)) {
                return $groupResults;
            }
        }

        // Ordenação
        if ($filter->sortBy) {
            $query->orderBy($filter->sortBy, $filter->sortDirection ?? 'asc');
        } else {
            $query->orderByDesc('visited_at');
        }

        // Paginação
        if ($filter->page && $filter->perPage) {
            return $query->paginate($filter->perPage, ['*'], 'page', $filter->page);
        }

        // Resposta padrão agrupada por mês se nenhum outro tipo de saída foi solicitado
        return $query->groupBy(DB::raw('MONTH(visited_at)'))
            ->select(DB::raw('MONTH(visited_at) as month, COUNT(*) as count'))
            ->get()
            ->keyBy('month')
            ->toArray();
    }
}
