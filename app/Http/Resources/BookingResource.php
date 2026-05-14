<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'lpk_id' => $this->lpk_id,
            
            // Sertakan data course jika sudah diload
            'course' => new CourseResource($this->whenLoaded('course')),
            
            // Sertakan data LPK jika sudah diload
            'lpk' => $this->whenLoaded('lpk'),
            
            'status' => $this->status,
            'total_price' => (float) $this->total_price,
            'booking_date' => $this->booking_date,
            'created_at' => $this->created_at,
        ];
    }
}
