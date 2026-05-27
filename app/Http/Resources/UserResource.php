<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'phone'                 => $this->phone,
            'email'                 => $this->email,
            'address'               => $this->address,
            'gender'                => $this->gender,
            'birth_date'            => $this->birth_date
                                        ? $this->birth_date->format('Y-m-d')
                                        : null,
            // URL foto yang bisa langsung dipakai di Image.network Flutter
            'photo_url'             => $this->photo
                                        ? Storage::url($this->photo)
                                        : null,
            'active_bookings_count' => $this->bookings()
                                        ->where('status', 'active')
                                        ->count(),
            'certificates_count'    => 0, // Sesuaikan kalau sudah ada tabel certificates
            'roles'                 => $this->getRoleNames(),
            'created_at'            => $this->created_at?->toISOString(),
        ];
    }
}