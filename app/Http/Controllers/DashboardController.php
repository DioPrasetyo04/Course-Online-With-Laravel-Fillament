<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;

class DashboardController extends Controller
{
    protected $transactionService;
    public function __construct(
        TransactionService $transactionService
    ) {
        $this->transactionService = $transactionService;
    }

    public function subscriptions()
    {
        $transactions = $this->transactionService->getUserTransactions();

        return view('front-courses.subscription', compact('transactions'));
    }

    public function subscriptionDetails(Transaction $transaction)
    {
        $detailPricing = $this->transactionService->detailFindPricingId($transaction->pricing_id);
        return view('front-courses.subscription-details', compact('transaction', 'detailPricing'));
    }
}
