<?php

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface
{
    // mempersiapkan method yang digunakan dalam transaction repository
    public function findTransactionId(string $bookingId): ?Transaction;
    public function createTransaction(array $data): Transaction;
    public function getUserTransactions(int $userId): Collection;
}
