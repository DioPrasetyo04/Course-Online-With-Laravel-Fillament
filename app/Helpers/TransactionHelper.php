<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Transaction;

class TransactionHelper
{
    public function generateTransactionId(User $user): string
    {
        $prefix = 'CourseLMS-';
        do {
            $transactionId = $prefix . $user->name . '-' . random_int(100000, 999999);
        } while (Transaction::where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }
}
