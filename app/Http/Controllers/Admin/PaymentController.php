<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // ── 1. Create Transaction (Midtrans) ──────────────────────────
    public function createTransaction(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        $user = $request->user();
        $orderId = 'SKL-' . time();

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id' => $user->id,
                'course_id' => $request->course_id,
                'code' => $orderId,
                'amount' => $request->amount,
                'status' => 'Menunggu', // Sesuai Constraint DB kamu
            ]);

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

            $snapToken = Snap::getSnapToken($params);
            $booking->update(['snap_token' => $snapToken]);

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => ['snap_token' => $snapToken, 'order_id' => $orderId]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── 2. Webhook Otomatis ──────────────────────────────────────
    public function webhook(Request $request)
    {
        try {
            $notif = new Notification();
            $booking = Booking::where('code', $notif->order_id)->first();

            if (!$booking)
                return response()->json(['status' => 'not_found'], 404);

            if ($notif->transaction_status == 'settlement') {
                // Update booking jadi Selesai (ACC)
                $booking->update(['status' => 'Selesai']);

                Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'user_id' => $booking->user_id,
                        'amount' => $booking->amount,
                        'status' => 'success', // Sesuai Constraint DB (payments_status_check)
                        'paid_at' => now(),
                    ]
                );
            }
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // ── 3. Update Status Manual oleh Admin ──────────────────────
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate(['status' => 'required']);

        // Mapping agar aman dari Error Constraint Violation
        // DB Payments cuma terima: 'pending', 'success', 'failed'
        $finalStatus = (in_array(strtolower($request->status), ['paid', 'lunas', 'success']))
            ? 'success'
            : $request->status;

        try {
            DB::beginTransaction();

            $payment->update(['status' => $finalStatus]);

            // Jika pembayaran sukses, booking otomatis dianggap Lunas/Selesai
            if ($finalStatus == 'success' && $payment->booking) {
                $payment->booking->update(['status' => 'Selesai']);
            }

            DB::commit();
            return back()->with('success', 'Pembayaran dikonfirmasi LUNAS!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}