<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Lpk extends Model
{
    use HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'legal_name',
        'phone',
        'wa_number',
        'nib',
        'location_id',
        'address',
        'lat',
        'long',
        'description',
        'facilities',
        'is_verified',
        'status',
        'status_verifikasi', // ← TAMBAH INI
        'contact_info',
        'logo',
        'images',
    ];

    protected $casts = [
        'facilities' => 'array',
        'contact_info' => 'array',
        'images' => 'array',
        'is_verified' => 'boolean',
        'lat' => 'decimal:8',
        'long' => 'decimal:8',
    ];

    protected $appends = [
        'logo_url',
        'gallery_images',
        'initials',
    ];

    /**
     * URL Logo / Foto Utama LPK
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!empty($this->logo)) {
            if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
                return $this->logo;
            }
            return asset('storage/' . ltrim($this->logo, '/'));
        }

        // Fallback: gunakan gambar pertama dari galeri jika tersedia
        $gallery = $this->gallery_images;
        if (!empty($gallery) && count($gallery) > 0) {
            return $gallery[0];
        }

        return null;
    }

    /**
     * Daftar URL Galeri Foto LPK
     */
    public function getGalleryImagesAttribute(): array
    {
        $raw = $this->images;
        if (empty($raw)) {
            return [];
        }

        $list = is_string($raw) ? json_decode($raw, true) : $raw;
        if (!is_array($list)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($item) {
            if (empty($item)) return null;
            if (str_starts_with($item, 'http://') || str_starts_with($item, 'https://')) {
                return $item;
            }
            return asset('storage/' . ltrim($item, '/'));
        }, $list)));
    }

    /**
     * Inisial cerdas tanpa awalan "LPK" agar avatar tidak seragam huruf "L"
     */
    public function getInitialsAttribute(): string
    {
        $name = trim($this->name ?? $this->legal_name ?? 'LPK');
        $cleaned = trim(preg_replace('/^(LPK|Lembaga\s+Pelatihan\s+Kerja|Yayasan|PT)\s+/i', '', $name));
        if (empty($cleaned)) {
            $cleaned = $name;
        }

        $words = preg_split('/\s+/', $cleaned);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            if (!empty($w)) {
                $initials .= mb_strtoupper(mb_substr($w, 0, 1));
            }
        }

        if (empty($initials)) {
            $initials = mb_strtoupper(mb_substr($name, 0, 2));
        }

        return $initials;
    }

    /**
     * URL Avatar fallback jika logo belum diupload
     */
    public function getAvatarFallbackUrlAttribute(): string
    {
        $clean = trim(preg_replace('/^(LPK|Lembaga\s+Pelatihan\s+Kerja|Yayasan|PT)\s+/i', '', $this->name ?? 'LPK'));
        return 'https://ui-avatars.com/api/?name=' . urlencode($clean) . '&background=2563eb&color=fff&bold=true&size=160';
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function verifications()
    {
        return $this->hasMany(LpkVerification::class);
    }
}