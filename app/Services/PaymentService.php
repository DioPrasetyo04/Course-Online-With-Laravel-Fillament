<?php

namespace App\Services;

use App\Models\Pricing;
use App\Helpers\TransactionHelper;
use TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PricingRepositoryInterface;

class PaymentService
{
    protected $midtransService;
    protected $pricingRepository;
    protected $transactionRepository;
    public function __construct(MidtransService $midtransService, PricingRepositoryInterface $pricingRepository, TransactionRepositoryInterface $transactionRepository)
    {
        $this->midtransService = $midtransService;
        $this->pricingRepository = $pricingRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function createPayment(int $pricingId)
    {
        $user = Auth::user();
        $pricing = $this->pricingRepository->findByIdPricing($pricingId);

        $tax = 0.11;
        $totalTax = $pricing->price * $tax;
        $grandTotal = $pricing->price + $totalTax;

        $transactionHelper = new TransactionHelper();
        $params = [
            'transaction_details' => [
                'order_id' => $transactionHelper->generateTransactionId($user),
                'gross_amount' => (int)$grandTotal,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'items_details' => [
                [
                    'id' => $pricing->id,
                    'price' => (int)$pricing->price,
                    'quantity' => 1,
                    'name' => $pricing->name
                ],
                [
                    'id' => 'tax-course',
                    'price' => (int)$totalTax,
                    'quantity' => 1,
                    'name' => 'PPN 11%'
                ]
            ],
            'custom_field1' => $user->id,
            'custom_field2' => $pricing->id,
        ];
        return $this->midtransService->createSnapToken($params);
    }

    public function handleNotification()
    {
        $notification = $this->midtransService->handleNotification();

        if (in_array($notification['transaction_status'], ['settlement', 'capture'])) {
            // $pricing = Pricing::findOrFail($notification['custom_field2']);
            $pricing = $this->pricingRepository->findByIdPricing($notification['custom_field2']);
            $this->createTransaction($notification, $pricing);
        }
        return $notification['transaction_status'];
    }

    public function createTransaction(array $notification, Pricing $pricing)
    {
        $startedAt = now();
        $endedAt = $startedAt->copy()->addMonths($pricing->duration);

        $transactionData = [
            'user_id' => $notification['custom_field1']->id,
            'pricing_id' => $notification['pricing']->id,
            'sub_total_amount' => $notification['sub_total_amount'],
            'total_tax_amount' => $notification['total_tax_amount'],
            'grand_total_amount' => $notification['grand_total_amount'],
            'is_paid' => true,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ];

        // proofnya bentuknya image
        if ($notification['proof']) {
            $transactionData['proof'] = $notification['proof'];
        }

        return $this->transactionRepository->createTransaction($transactionData);
    }
}
