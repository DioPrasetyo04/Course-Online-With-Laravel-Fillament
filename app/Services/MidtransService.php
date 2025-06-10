<?php

namespace App\Services;

use Exception;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use Throwable;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$clientKey = config('midtrans.clientKey');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');
    }

    public function createSnapToken(array $params): string
    {
        try {
            return Snap::getSnapToken($params);
        } catch (Exception $e) {
            Log::error('Failed get snap token: ' . $e->getMessage());
            throw $e;
        }
    }

    //menerima callback notifikasi midtrans setelah transaksi
    public function handleNotification()
    {
        try {
            $notification = new Notification();
            return [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'gross_amount' => $notification->gross_amount,
                'custom_field1' => $notification->custom_field1,
                'custom_field2' => $notification->custom_field2
            ];
        } catch (Throwable $e) {
            Log::error('Failed notification midtrans: ' . $e->getMessage());
        }
    }
}
