<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        // Set Midtrans Config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Contoh: ambil total harga dari request
        $amount = $request->amount;

        // Order ID unik
        $orderId = 'ORDER-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => 'Dheva',
                'email' => 'example@gmail.com'
            ]
        ];

        // Dapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snapToken' => $snapToken,
            'order_id' => $orderId,
        ]);
    }
}
