<?php

namespace App\Services;

use App\Models\Pricing;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PricingRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;

class TransactionService
{
    protected $pricingRepository;
    protected $TransactionRepository;

    public function __construct(PricingRepositoryInterface $pricingRepository, TransactionRepositoryInterface $TransactionRepository)
    {
        $this->pricingRepository = $pricingRepository;
        $this->TransactionRepository = $TransactionRepository;
    }

    public function prepareCheckout(Pricing $pricing)
    {
        $user = Auth::user();

        $alreadySubscribed = $pricing->isSubscribedByUser($user->id);

        $tax = 0.11;
        $total_tax_amount = $pricing->price * $tax;
        $sub_total_amount = $pricing->price;
        $grand_total_amount = $sub_total_amount + $total_tax_amount;

        $started_at = now();
        $ended_at = $started_at->copy()->addMonths($pricing->duration);

        session()->put('pricing_id', $pricing->id);

        return compact(
            'total_tax_amount',
            'sub_total_amount',
            'grand_total_amount',
            'pricing',
            'alreadySubscribed',
            'user',
            'started_at',
            'ended_at'
        );
    }

    public function getRecentPricing()
    {
        $pricingId = session()->get('pricing_id');
        return $this->pricingRepository->findByIdPricing($pricingId);
    }

    public function getUserTransactions()
    {
        // tanpa menggunakan repository

        // $user = Auth::user();

        // if (!$user) {
        //     return collect([]);
        // }

        // return Transaction::with('pricing')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();


        // menggunakan repository
        $user = Auth::user();
        return $this->TransactionRepository->getUserTransactions($user->id);
    }

    public function findTransactionId(string $bookingId)
    {
        return $this->TransactionRepository->findTransactionId($bookingId);
    }

    public function detailFindPricingId(int $pricingId)
    {
        return $this->TransactionRepository->detailFindPricingId($pricingId);
    }
}
