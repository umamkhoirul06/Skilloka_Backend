<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Translasi status kembali ke format konsisten agar Flutter (isPending, isCompleted, dll) bekerja akurat
        $statusMap = [
            'Menunggu' => 'pending',
            'pending' => 'pending',
            'Menunggu Pembayaran' => 'pending',
            'confirmed' => 'confirmed',
            'paid' => 'paid',
            'sedang_belajar' => 'ongoing',
            'Selesai' => 'completed',
            'completed' => 'completed',
            'Dibatalkan' => 'cancelled',
            'cancelled' => 'cancelled',
        ];

        return [
            'id' => $this->id,
            'code' => $this->id, // Menggunakan id sebagai code karena tabel baru tidak ada code
            'status' => $statusMap[$this->status] ?? $this->status,
            'booking_status' => $this->status,
            'status_belajar' => $this->status_belajar ?? 'sedang_belajar',
            'amount' => (float) $this->total_price, // Diubah kembali ke amount
            'expires_at' => null,
            'created_at' => $this->booking_date,
            
            // Bungkus ke dalam object schedule agar cocok dengan UI Flutter
            'schedule' => [
                'id' => $this->id,
                'courseId' => $this->course_id,
                'courseTitle' => $this->whenLoaded('course', fn() => $this->course->title),
                'courseImageUrl' => $this->whenLoaded('course', function() {
                    return !empty($this->course->images) ? $this->course->images[0] : null;
                }),
                'lpkName' => $this->whenLoaded('lpk', fn() => $this->lpk->name),
                'lpkLogoUrl' => $this->whenLoaded('lpk', fn() => $this->lpk->logo),
                'categoryName' => $this->whenLoaded('course', function() {
                    return $this->course->relationLoaded('category') ? $this->course->category->name : null;
                }),
            ],
        ];
    }
}
