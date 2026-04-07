<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    //
    use HasFactory;

    protected $table = 'movies';  // 指定正确的表名

    protected $fillable = [
        'title',
        'description',
        'duration',
        'poster',
        'release_date',
        'is_showing'
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_showing' => 'boolean',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
