<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Banner extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'lpk_id',
        'title',
        'image_path',
        'is_active',
    ];

    public function lpk()
    {
        return $this->belongsTo(Lpk::class);
    }
}