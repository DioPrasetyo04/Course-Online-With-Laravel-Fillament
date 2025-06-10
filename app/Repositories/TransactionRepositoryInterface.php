<?php

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface
{
    public function findBookingId(string $bookingId): ?Transaction;
    public function createTransaction(array $data): Transaction;
    public function getUserTransactions(int $userId): Collection;
}
