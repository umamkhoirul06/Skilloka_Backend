<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Translasi status kembali ke bahasa Inggris agar Flutter (isPending, isCompleted, dll) tidak rusak
        $statusMap = [
            'Menunggu' => 'pending',
            'Selesai' => 'completed',
            'Dibatalkan' => 'cancelled',
        ];

        return [
            'id' => $this->id,
            'code' => $this->id, // Menggunakan id sebagai code karena tabel baru tidak ada code
            'status' => $statusMap[$this->status] ?? 'pending',
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
