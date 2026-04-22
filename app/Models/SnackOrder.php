<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SnackOrder extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'items',
        'total_amount',
        'order_number',
        'status',
    ];

    protected $casts = [
        'items' => 'json',
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
