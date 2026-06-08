<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        $user = $request->user();
        $orderId = 'SKL-' . time();

        // 1. Simpan ke Booking
        $booking = Booking::create([
            'user_id' => $user->id,
            'course_id' => $request->course_id,
            'code' => $orderId,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        // 2. Request ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $booking->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'data' => ['snap_token' => $snapToken, 'order_id' => $orderId]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 3. Webhook untuk update status (Midtrans nembak ke sini)
    public function webhook(Request $request)
    {
        $notif = new Notification();
        $booking = Booking::where('code', $notif->order_id)->first();

        if ($notif->transaction_status == 'settlement') {
            $booking->update(['status' => 'confirmed']);

            // Catat di tabel Payments
            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $booking->amount,
                'status' => 'success',
                'paid_at' => now(),
            ]);
        }
        return response()->json(['status' => 'ok']);
    }
}