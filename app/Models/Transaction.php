<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'tenant_id',
        'invoice',
        'amount',
        'status'
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    // relasi user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tambahkan protected $casts di App\Models\Transaction
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // relasi tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}