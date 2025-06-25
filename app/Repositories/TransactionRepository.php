<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    // koneksi dengan model Transaction dan mengimplementasikan method yang ada di transaction repository interface bisnis logic
    protected $transaction;
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function findTransactionId(string $bookingId): Transaction|null
    {
        return $this->transaction->where('transaction_id', $bookingId)->first();
    }

    public function createTransaction(array $data): Transaction
    {
        return $this->transaction->create($data);
    }

    public function getUserTransactions(int $userId): Collection
    {
        return $this->transaction->with('pricing')->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }


    public function detailFindPricingId(int $pricingId): ?Transaction
    {
        return $this->transaction->with('pricing')->where('pricing_id', $pricingId)->first();
    }
}
