<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\TransactionService;

class DashboardController extends Controller
{
    protected $transactionService;
    public function __construct(
        TransactionService $transactionService
    ) {
        $this->transactionService = $transactionService;
    }

    public function subscription()
    {
        $transactions = $this->transactionService->getUserTransactions();

        return view('front.subscriptions', compact('transactions'));
    }

    public function subscriptionDetails(Transaction $transaction)
    {
        $detailPricing = $this->transactionService->detailFindPricingId($transaction->pricing_id);
        return view('front.subscription_details', compact('transaction', 'detailPricing'));
    }
}
