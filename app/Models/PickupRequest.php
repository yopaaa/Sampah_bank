<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupRequest extends Model
{
    protected $fillable = [
        'user_id',
        'notes',
        'lokasi',
        'koordinat',
        'status',
        'bukti',
        'jumlah_plastik'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
