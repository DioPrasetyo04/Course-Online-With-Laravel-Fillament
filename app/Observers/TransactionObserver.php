<?php

namespace App\Observers;

use App\Helpers\TransactionHelper;
use App\Models\Transaction;

class TransactionObserver
{
    public function creating(Transaction $transaction)
    {
        $helper = new TransactionHelper();
        $transaction->transaction_id = $helper->generateTransactionId($transaction->student);
    }
}
