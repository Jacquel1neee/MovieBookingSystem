<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'hall_id', 'row', 'column', 'seat_number', 'type',
    ];

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_seats')
            ->withPivot('price')
            ->withTimestamps();
    }
}
