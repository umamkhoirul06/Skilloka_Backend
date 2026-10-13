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

            'birth_date' => $this->birth_date
                ? Carbon::parse($this->birth_date)->format('Y-m-d')
                : null,

            'photo_url' => $this->photo
                ? url('storage/' . $this->photo)
                : null,

            // Preferensi avatar dan frame
            'avatar_type' => $this->avatar_type ?? 'photo',
            'avatar_id' => $this->avatar_id,
            'frame_id' => $this->frame_id ?? 'none',

            'active_bookings_count' => $this->bookings()
                ->where('status', 'active')
                ->count(),

            'certificates_count' => 0,
            'roles' => $this->getRoleNames(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}