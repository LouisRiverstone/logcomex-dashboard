<?php

namespace App\UseCases\Dashboard;

use App\DTOs\Dashboard\TransactionFilterDTO;
use App\DTOs\Dashboard\Output\TransactionsOutputDTO;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;

class GetTransactionsUseCase
{
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(?TransactionRepositoryInterface $transactionRepository = null)
    {
        $this->transactionRepository = $transactionRepository ?? new TransactionRepository();
    }

    /**
     * @param TransactionFilterDTO|null $filter
     * @return TransactionsOutputDTO
     */
    public function execute(?TransactionFilterDTO $filter): TransactionsOutputDTO
    {
        // Cria um filtro vazio se for null
        if ($filter === null) {
            $filter = new TransactionFilterDTO();
        }

        // Usa o método getFilteredTransactions
        $transactions = $this->transactionRepository->getFilteredTransactions($filter);

        // Converte os itens para o formato esperado pelo DTO
        $items = [];

       
        foreach ($transactions->items() as $key => $item) {
            $customer = $item->customer ?? null;
            $email = $item->email ?? null;


            $items['transaction_' . $key] = [
                'id' => $item->id,
                'type' => $item->type,
                'customer' => $customer,
                'email' => $email,
                'amount' => $item->amount,
                'status' => $item->status,
                'description' => $item->description,
                'created_at' => $item->created_at,
            ];
        }

        return TransactionsOutputDTO::fromArray([
            'data' => $items,
            'meta' => [
                'total' => $transactions->total(),
                'per_page' => $transactions->perPage(),
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
            ]
        ]);
    }

}
