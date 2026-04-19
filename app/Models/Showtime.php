<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id', 'hall_id', 'start_time', 'end_time', 'price', 'vip_price',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // 获取已预订的座位ID
    public function getBookedSeats()
    {
        $bookedSeats = [];
        foreach ($this->bookings as $booking) {
            if ($booking->status != 'cancelled') {
                foreach ($booking->seats as $seat) {
                    $bookedSeats[] = $seat->id;
                }
            }
        }

        return $bookedSeats;
    }
}
