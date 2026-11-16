<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LpkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'description' => $this->description,
            'address' => $this->address,
            'phone' => $this->phone,
            'wa_number' => $this->wa_number,
            'nib' => $this->nib,
            'logo_url' => $this->logo_url,
            'initials' => $this->initials,
            'images' => $this->gallery_images,
            'rating' => (float) ($this->rating ?? 0),
            'review_count' => (int) ($this->review_count ?? 0),
            'is_verified' => (bool) $this->is_verified,
            'lat' => $this->lat,
            'long' => $this->long,
            'facilities' => $this->facilities ?? [],
            'contact_info' => is_string($this->contact_info)
                ? json_decode($this->contact_info, true)
                : $this->contact_info,
            'location' => $this->whenLoaded('location', fn() => [
                'id' => $this->location->id,
                'name' => $this->location->name,
            ]),
            'courses_count' => $this->whenCounted('courses'),
            'status' => $this->status,
        ];
    }
}
