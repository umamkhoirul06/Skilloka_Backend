<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpkVerification extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'verified_at' => 'datetime',
        'documents' => 'array',
    ];

    public function lpk()
    {
        return $this->belongsTo(Lpk::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
