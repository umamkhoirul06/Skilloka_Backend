<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LpkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'legal_name'    => $this->legal_name,
            'address'       => $this->address,
            'logo_url'      => $this->logo ? asset('storage/' . $this->logo) : null,
            'images'        => collect($this->images ?? [])->map(
                fn($img) => asset('storage/' . $img)
            )->toArray(),
            'rating'        => (float) ($this->rating ?? 0),
            'review_count'  => (int) ($this->review_count ?? 0),
            'is_verified'   => (bool) $this->is_verified,
            'lat'           => $this->lat,
            'long'          => $this->long,
            'facilities'    => $this->facilities ?? [],
            'contact_info'  => $this->contact_info ?? [],
            'location'      => $this->whenLoaded('location', fn() => [
                'id'   => $this->location->id,
                'name' => $this->location->name,
            ]),
            'courses_count' => $this->whenCounted('courses'),
            'status'        => $this->status,
        ];
    }
}
