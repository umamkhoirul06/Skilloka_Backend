<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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

            // 🔥 FIX 1: Tambahkan Description agar HP tidak nampilin tulisan Dummy!
            'description' => $this->description,

            'lpk' => $this->lpk ? [
                'id' => $this->lpk->id,
                'name' => $this->lpk->name,
                // 🔥 FIX 2: Bungkus logo LPK pakai url() agar jadi Absolute URL
                'logo' => $this->lpk->logo ? url('storage/' . $this->lpk->logo) : null,
            ] : null,

            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,

            // 🔥 FIX 3: Pastikan images aman kalau kosong, dan bungkus pakai url()
            'images' => is_array($this->images) ? collect($this->images)->map(function ($img) {
                return url('storage/' . $img);
            })->toArray() : [],

            'level' => $this->level,

            // 🔥 FIX 4: Pastikan facilities aman kalau wujudnya masih string JSON
            'facilities' => is_string($this->facilities)
                ? json_decode($this->facilities, true)
                : ($this->facilities ?? []),

            // 🔥 FIX FINAL: Tambahkan schedules di sini agar lolos sensor API Resource!
            // Kita petakan datanya menjadi format 'date' dan 'time' sesuai kebutuhan UI Flutter
            'schedules' => $this->schedules ? $this->schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    // Format tanggal menjadi: "08 Jun 2026 s.d 23 Dec 2026"
                    'date' => Carbon::parse($schedule->start_date)->format('d M Y') . ' s.d ' . Carbon::parse($schedule->end_date)->format('d M Y'),
                    // Format jam menjadi: "08:00 - 12:00 (Senin-Rabu-Jumat)"
                    'time' => substr($schedule->daily_start, 0, 5) . ' - ' . substr($schedule->daily_end, 0, 5) . ' (' . $schedule->days_of_week . ')',
                    'slots' => $schedule->max_capacity,
                    'status' => $schedule->status,
                ];
            })->toArray() : [],
        ];
    }
}