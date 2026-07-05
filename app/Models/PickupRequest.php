<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupRequest extends Model
{
    protected $fillable = [
        'user_id',
        'agent_id',
        'notes',
        'lokasi',
        'koordinat',
        'status',
        'bukti',
        'jumlah_plastik',
        'jenis_sampah',
        'total_harga'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
