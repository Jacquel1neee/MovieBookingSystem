<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'is_showing',
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_showing' => 'boolean',
    ];

    public function getPosterUrlAttribute()
    {
        if (! $this->poster) {
            return null;
        }

        if (Str::startsWith($this->poster, ['http://', 'https://'])) {
            return $this->poster;
        }

        return asset('storage/'.ltrim($this->poster, '/'));
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
