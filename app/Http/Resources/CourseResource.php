<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => (float) $this->price,
            'duration_hours' => $this->duration_hours,
            'lpk' => $this->lpk ? [
                'id' => $this->lpk->id,
                'name' => $this->lpk->name,
                'logo' => $this->lpk->logo,
            ] : null,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,
            'images' => $this->images,
            'level' => $this->level,
        ];
    }
}
