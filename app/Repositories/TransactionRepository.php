<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\DTOs\Dashboard\TransactionFilterDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @param TransactionFilterDTO $filter
     * @return LengthAwarePaginator<Transaction>
     */
    public function getFilteredTransactions(TransactionFilterDTO $filter): LengthAwarePaginator
    {
        $query = DB::table('transactions')
            ->select([
                'transactions.id as id',
                'transactions.type as type',
                'users.name as customer',
                'users.email as email',
                'transactions.amount as amount',
                'transactions.status as status',
                'transactions.description as description',
                'transactions.created_at as created_at'
            ])
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id');


        if ($filter->type) {
            $query->where('transactions.type', $filter->type);
        }

        if ($filter->status) {
            $query->where('transactions.status', $filter->status);
        }

        if ($filter->startDate) {
            $query->where('transactions.created_at', '>=', $filter->startDate);
        }

        if ($filter->endDate) {
            $query->where('transactions.created_at', '<=', $filter->endDate);
        }

        if ($filter->minAmount) {
            $query->where('transactions.amount', '>=', $filter->minAmount);
        }

        if ($filter->maxAmount) {
            $query->where('transactions.amount', '<=', $filter->maxAmount);
        }

        if ($filter->customer) {
            $query->where('users.name', 'like', '%' . $filter->customer . '%');
        }

        if ($filter->email) {
            $query->where('users.email', 'like', '%' . $filter->email . '%');
        }

        if ($filter->description) {
            $query->where('transactions.description', 'like', '%' . $filter->description . '%');
        }

        if ($filter->sortBy && $filter->sortDirection) {
            $query->orderBy($filter->sortBy, $filter->sortDirection);
        }

        return $query->paginate($filter->perPage ?? 15, ['*'], 'page', $filter->page ?? 1);
    }
}
