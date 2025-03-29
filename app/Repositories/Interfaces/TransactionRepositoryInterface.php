<?php

namespace App\Repositories\Interfaces;

use App\DTOs\Dashboard\TransactionFilterDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    /**
     * @param TransactionFilterDTO $filter
     * @return LengthAwarePaginator<Transaction>
     */
    public function getFilteredTransactions(TransactionFilterDTO $filter): LengthAwarePaginator;
}
