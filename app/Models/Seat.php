<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'hall_id', 'row', 'column', 'seat_number', 'type'
    ];

    // 定义与 Hall 的关系
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    // 定义与 Booking 的关系
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_seats')
                    ->withPivot('price')
                    ->withTimestamps();
    }
}