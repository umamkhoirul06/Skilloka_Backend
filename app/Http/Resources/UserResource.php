<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'gender' => $this->gender,

            // FIX 1: Memastikan tidak error meskipun birth_date di DB berbentuk string
            'birth_date' => $this->birth_date
                ? Carbon::parse($this->birth_date)->format('Y-m-d')
                : null,

            // FIX 2: Memaksa Laravel mengirim Absolute URL (https://skilloka.my.id/...) 
            // agar Image.network di Flutter tidak nge-blank.
            'photo_url' => $this->photo
                ? url('storage/' . $this->photo)
                : null,

            'active_bookings_count' => $this->bookings()
                ->where('status', 'active')
                ->count(),
            'certificates_count' => 0, // Sesuaikan kalau sudah ada tabel certificates
            'roles' => $this->getRoleNames(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}