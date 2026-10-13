<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;


class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Membuat transaksi Midtrans untuk booking milik pengguna login.
     */
    public function createTransaction(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'string', 'exists:bookings,id'],
        ]);

        $booking = Booking::with('schedule')
            ->where('id', $validated['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($booking->status === 'Selesai') {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking ini sudah diselesaikan.',
            ], 422);
        }

        $existingPayment = Payment::where('booking_id', $booking->id)
            ->where('status', 'success')
            ->exists();

        if ($existingPayment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking ini sudah dibayar.',
            ], 422);
        }

        try {
            $orderId = 'SKL-' . str_replace('-', '', $booking->id) . '-' . strtoupper(Str::random(8));

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $booking->total_price,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'phone' => $request->user()->phone ?? '',
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $booking->update([
                'snap_token' => $snapToken,
            ]);

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'amount' => (float) $booking->total_price,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal membuat transaksi Midtrans', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi belum dapat dibuat.',
            ], 500);
        }
    }

    /**
     * Pembayaran dummy untuk pengembangan.
     * Hanya untuk akun pemilik booking yang belum dibayar.
     */
    public function confirmDummy(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'string', 'exists:bookings,id'],
            'method' => ['required', 'in:transfer_bank,qris,e_wallet'],
        ]);

        $user = $request->user();

        try {
            $payment = DB::transaction(function () use ($validated, $user) {
                $booking = Booking::where('id', $validated['booking_id'])
                    ->where('user_id', $user->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $existingPayment = Payment::where('booking_id', $booking->id)
                    ->lockForUpdate()
                    ->first();

                if ($existingPayment && $existingPayment->status === 'success') {
                    abort(422, 'Booking ini sudah dibayar.');
                }

                $payment = Payment::updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'user_id' => $booking->user_id,
                        'tenant_id' => $booking->tenant_id,
                        'method' => $validated['method'],
                        'provider' => 'Skilloka Dummy',
                        'external_id' => 'DUMMY-' . Str::uuid(),
                        'amount' => $booking->total_price,
                        'status' => 'success',
                        'paid_at' => now(),
                        'notes' => 'Pembayaran dummy untuk pengembangan.',
                    ]
                );

                $booking->update([
                    'status' => 'Selesai',
                    'payment_method' => $validated['method'],
                    'status_belajar' => 'sedang_belajar',
                ]);

                return $payment;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran dummy berhasil.',
                'payment' => $payment,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan atau bukan milik akun ini.',
            ], 404);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Throwable $e) {
            Log::error('Pembayaran dummy gagal', [
                'booking_id' => $validated['booking_id'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran gagal diproses.',
            ], 500);
        }
    }

    /**
     * Webhook Midtrans.
     * Memastikan signature sebelum memperbarui pembayaran.
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . Config::$serverKey
        );

        if (
            $signatureKey === '' ||
            ! hash_equals($expectedSignature, $signatureKey)
        ) {
            Log::warning('Signature webhook Midtrans tidak valid.');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        try {
            $bookingId = str_starts_with($orderId, 'SKL-')
    ? substr($orderId, 4, 32)
    : $orderId;

if (strlen($bookingId) === 32) {
    $bookingId = substr($bookingId, 0, 8) . '-' .
        substr($bookingId, 8, 4) . '-' .
        substr($bookingId, 12, 4) . '-' .
        substr($bookingId, 16, 4) . '-' .
        substr($bookingId, 20, 12);
}

$booking = Booking::where('id', $bookingId)->firstOrFail();

            $transactionStatus = $payload['transaction_status'] ?? '';
            $fraudStatus = $payload['fraud_status'] ?? '';

            if (
                $transactionStatus === 'settlement' ||
                (
                    $transactionStatus === 'capture' &&
                    $fraudStatus === 'accept'
                )
            ) {
                DB::transaction(function () use ($booking, $payload) {
                    $lockedBooking = Booking::where('id', $booking->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    Payment::updateOrCreate(
                        ['booking_id' => $lockedBooking->id],
                        [
                            'user_id' => $lockedBooking->user_id,
                            'tenant_id' => $lockedBooking->tenant_id,
                            'method' => $payload['payment_type'] ?? 'midtrans',
                            'provider' => 'Midtrans',
                            'external_id' => $payload['transaction_id'] ?? $payload['order_id'],
                            'amount' => $lockedBooking->total_price,
                            'status' => 'success',
                            'paid_at' => now(),
                            'notes' => 'Pembayaran dikonfirmasi oleh webhook Midtrans.',
                        ]
                    );

                    $lockedBooking->update([
                        'status' => 'Selesai',
                        'status_belajar' => 'sedang_belajar',
                    ]);
                });
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'], true)) {
                Payment::where('booking_id', $booking->id)
                    ->where('status', '!=', 'success')
                    ->update(['status' => 'failed']);
            }

            return response()->json(['message' => 'Webhook diterima']);
        } catch (\Throwable $e) {
            Log::error('Pemrosesan webhook Midtrans gagal', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Webhook gagal diproses'], 500);
        }
    }
}
