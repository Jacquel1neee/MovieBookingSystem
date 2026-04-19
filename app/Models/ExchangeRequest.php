<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRequest extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'request_number',
        'user_id',
        'booking_id',
        'new_showtime_id',
        'reason',
        'selected_seat_ids',
        'admin_remarks',
        'status',
    ];

    protected $casts = [
        'selected_seat_ids' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            $request->request_number = 'ER'.strtoupper(uniqid());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function newShowtime()
    {
        return $this->belongsTo(Showtime::class, 'new_showtime_id');
    }
}
