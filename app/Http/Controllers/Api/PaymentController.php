<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
    }

    // 1. FUNGSI UNTUK MOBILE MINTA TOKEN PEMBAYARAN
    public function createTransaction(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string|unique:transactions,invoice', // Sesuaikan dengan tabel transaksimu
            'gross_amount' => 'required|numeric',
        ]);

        // Detail pesanan yang akan dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $request->order_id,
                'gross_amount' => $request->gross_amount,
            ],
            'customer_details' => [
                'first_name' => $request->user()->name ?? 'User Mobile',
                'email' => $request->user()->email ?? 'user@skilloka.com',
                'phone' => $request->user()->phone ?? '08123456789',
            ],
        ];

        try {
            // Minta Snap Token ke Midtrans
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // 2. FUNGSI UNTUK MIDTRANS LAPOR KALAU USER UDAH BAYAR (WEBHOOK)
    public function callback(Request $request)
    {
        try {
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;
            $fraudStatus = $notif->fraud_status;

            // Catat di log Laravel biar ketahuan kalau ada yang bayar
            Log::info("Midtrans Callback - Order ID: {$orderId} | Status: {$transactionStatus}");

            // Di sini nanti logika update status di database kamu
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                // UPDATE status transaksi jadi LUNAS (Paid) di database
                // Transaction::where('invoice', $orderId)->update(['status' => 'paid']);
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                // UPDATE status transaksi jadi GAGAL/KEDALUWARSA (Failed) di database
                // Transaction::where('invoice', $orderId)->update(['status' => 'failed']);
            }

            return response()->json(['message' => 'Callback diterima']);
        } catch (\Exception $e) {
            Log::error("Midtrans Error: " . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}