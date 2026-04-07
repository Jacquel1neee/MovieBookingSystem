<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Movie;
use App\Models\Hall;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user if not exists
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        // Create sample movies
        Movie::create([
            'title' => 'Inception',
            'description' => 'A skilled thief who steals corporate secrets through the use of dream-sharing technology.',
            'duration' => 148,
            'release_date' => now()->subDays(30),
            'is_showing' => 1,
            'poster' => 'inception.jpg',
            'director' => 'Christopher Nolan',
            'cast' => 'Leonardo DiCaprio, Ellen Page, Tom Hardy',
        ]);

        Movie::create([
            'title' => 'The Matrix',
            'description' => 'A computer hacker learns from mysterious rebels about the true nature of his reality.',
            'duration' => 136,
            'release_date' => now()->subDays(60),
            'is_showing' => 1,
            'poster' => 'matrix.jpg',
            'director' => 'The Wachowskis',
            'cast' => 'Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss',
        ]);

        Movie::create([
            'title' => 'Interstellar',
            'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
            'duration' => 169,
            'release_date' => now()->addDays(10),
            'is_showing' => 0,
            'poster' => 'interstellar.jpg',
            'director' => 'Christopher Nolan',
            'cast' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
        ]);

        // Create sample halls
        Hall::create([
            'name' => 'Hall A',
            'rows' => 10,
            'columns' => 12,
            'total_seats' => 120,
        ]);

        Hall::create([
            'name' => 'Hall B',
            'rows' => 8,
            'columns' => 10,
            'total_seats' => 80,
        ]);
    }
}

