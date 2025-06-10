<?php

use App\Models\Pricing;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PricingRepository;

class TransactionService
{
    protected $pricingRepository;
    protected $TransactionRepository;

    public function __construct(PricingRepository $pricingRepository, TransactionRepository $TransactionRepository)
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
        if (!$user) {
            return collect([]);
        }
        return $this->TransactionRepository->getUserTransactions($user->id);
    }

    public function createBooking(Pricing $pricing): Transaction
    {
        $checkoutData = $this->prepareCheckout($pricing);

        $transactionData = [
            'user_id' => $checkoutData['user']->id,
            'pricing_id' => $checkoutData['pricing']->id,
            'sub_total_amount' => $checkoutData['sub_total_amount'],
            'total_tax_amount' => $checkoutData['total_tax_amount'],
            'grand_total_amount' => $checkoutData['grand_total_amount'],
            'is_paid' => false,
            'started_at' => $checkoutData['started_at'],
            'ended_at' => $checkoutData['ended_at'],
        ];

        if ($checkoutData['alreadySubscribed']) {
            $transactionData['is_paid'] = true;
        }

        // proofnya bentuknya image
        if ($checkoutData['proof']) {
            $transactionData['proof'] = $checkoutData['proof'];
        }

        return $this->TransactionRepository->createBooking($transactionData);
    }

    public function findBookingId(string $bookingId)
    {
        return $this->TransactionRepository->findBookingId($bookingId);
    }
}
