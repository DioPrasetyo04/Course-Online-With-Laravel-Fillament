<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\Pricing;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\PricingService;
use Illuminate\Support\Facades\Log;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\PricingResource;

class FrontHomeController extends Controller
{
    protected $transactionService;
    protected $paymentService;
    protected $pricingService;

    public function __construct(
        PaymentService $paymentService,
        TransactionService $transactionService,
        PricingService $pricingService
    ) {
        $this->paymentService = $paymentService;
        $this->transactionService = $transactionService;
        $this->pricingService = $pricingService;
    }
    public function index()
    {
        return view('front.index');
    }

    public function pricing()
    {
        $pricing_packages = $this->pricingService->getAllPricing();
        $user = Auth::user();
        return view('front.pricing', compact('pricing_packages', 'user'));
    }

    public function checkout(Pricing $pricing)
    {
        $checkoutData = $this->transactionService->prepareCheckout($pricing);

        if ($checkoutData['alreadySubscribed']) {
            return redirect()->route('front.pricing')->with('error', 'You are already subscribed to this package.');
        }

        return view('front.checkout', $checkoutData);
    }

    public function paymentStoreMidtrans()
    {
        try {
            $pricingId = session()->get('pricing_id');

            if (!$pricingId) {
                return redirect()->route('front.pricing')->with(['error', 'Pricing not found'], 404);
            }

            $snapToken = $this->paymentService->createPayment($pricingId);

            if (!$snapToken) {
                return response()->json([
                    'error' => 'Failed to create Midtrans transactions'
                ], 500);
            }

            return response()->json([
                'snapToken' => $snapToken
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'payment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function paymentCallbackMidtrans(Request $request)
    {
        try {
            $transactionStatus = $this->paymentService->handleNotification();

            if (!$transactionStatus) {
                return response()->json([
                    'error' => 'Invalid notification data'
                ], 400);
            }

            return response()->json([
                'status' => $transactionStatus
            ]);
        } catch (Exception $e) {
            Log::error('Failed to handle midtrans notification: ', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Failed to handle midtrans notification'
            ], 500);
        }
    }

    public function checkout_success()
    {
        $pricing = $this->transactionService->getRecentPricing();

        if (!$pricing) {
            return redirect()->route('front.pricing')->with('error', 'Pricing not found');
        }

        return view('front.checkout_success', compact('pricing'));
    }
}
