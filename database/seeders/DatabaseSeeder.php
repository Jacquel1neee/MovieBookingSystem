<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\ExchangeRequest;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => Hash::make('password'), 'is_admin' => false]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => Hash::make('Abc12345'), 'is_admin' => true]
        );

        // 2. Create Movies
        $movies = [
            Movie::firstOrCreate(['title' => 'Inception'], [
                'description' => 'A skilled thief who steals corporate secrets through the use of dream-sharing technology.',
                'duration' => 148, 'release_date' => now()->subDays(30), 'is_showing' => 1, 'poster' => 'https://image.tmdb.org/t/p/w500/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg',
            ]),
            Movie::firstOrCreate(['title' => 'The Matrix'], [
                'description' => 'A computer hacker learns from mysterious rebels about the true nature of his reality.',
                'duration' => 136, 'release_date' => now()->subDays(60), 'is_showing' => 1, 'poster' => 'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg',
            ]),
            Movie::firstOrCreate(['title' => 'Dune: Part Two'], [
                'description' => 'Paul Atreides unites with Chani and the Fremen while on a warpath of revenge against the conspirators who destroyed his family.',
                'duration' => 166, 'release_date' => now()->subDays(15), 'is_showing' => 1, 'poster' => 'https://image.tmdb.org/t/p/original/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg',
            ]),
            Movie::firstOrCreate(['title' => 'The Batman'], [
                'description' => 'When a sadistic serial killer begins murdering key political figures in Gotham, Batman is forced to investigate the city\'s hidden corruption.',
                'duration' => 176, 'release_date' => now()->subDays(45), 'is_showing' => 1, 'poster' => 'https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg',
            ]),
            Movie::firstOrCreate(['title' => 'Spider-Man: No Way Home'], [
                'description' => 'With Spider-Man\'s identity now revealed, Peter asks Doctor Strange for help.',
                'duration' => 148, 'release_date' => now()->subDays(10), 'is_showing' => 1, 'poster' => 'https://image.tmdb.org/t/p/original/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
            ]),
            Movie::firstOrCreate(['title' => 'Deadpool & Wolverine'], [
                'description' => 'A listless Wade Wilson toils away in civilian life with his days as the morally flexible mercenary, Deadpool, behind him.',
                'duration' => 127, 'release_date' => now()->addDays(5), 'is_showing' => 0, 'poster' => 'https://image.tmdb.org/t/p/w500/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg',
            ]),
            Movie::firstOrCreate(['title' => 'Interstellar'], [
                'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
                'duration' => 169, 'release_date' => now()->addDays(10), 'is_showing' => 0, 'poster' => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
            ]),
        ];

        // 3. Create Halls and Seats
        $hallsData = [
            ['name' => 'Hall A', 'rows' => 8, 'columns' => 10, 'experience_type' => 'IMAX'],
            ['name' => 'Hall B', 'rows' => 5, 'columns' => 8, 'experience_type' => 'Standard'],
            ['name' => 'Hall C', 'rows' => 9, 'columns' => 14, 'experience_type' => 'Standard'],
            ['name' => 'Hall D', 'rows' => 7, 'columns' => 12, 'experience_type' => 'Dolby Atmos'],
            ['name' => 'Gold Class', 'rows' => 4, 'columns' => 6, 'experience_type' => 'Gold Class'],
        ];

        $halls = [];
        foreach ($hallsData as $data) {
            $hall = Hall::firstOrCreate(['name' => $data['name']], [
                'rows' => $data['rows'],
                'columns' => $data['columns'],
                'total_seats' => $data['rows'] * $data['columns'],
                'experience_type' => $data['experience_type'] ?? 'Standard',
            ]);
            $halls[] = $hall;

            if ($hall->seats()->count() == 0) {
                $rowsAlphabet = range('A', 'Z');
                for ($row = 0; $row < $data['rows']; $row++) {
                    for ($col = 1; $col <= $data['columns']; $col++) {
                        $type = 'regular';
                        // Make the last row VIP seats
                        if ($row == $data['rows'] - 1) {
                            $type = 'vip';
                        }
                        Seat::create([
                            'hall_id' => $hall->id,
                            'row' => $row + 1, // Store as an integer (1, 2, 3...)
                            'column' => $col,
                            'seat_number' => $rowsAlphabet[$row].$col, // A1, A2, B1...
                            'type' => $type, // Match the enum ['regular', 'vip'] in migration
                        ]);
                    }
                }
            }
        }

        // 4. Create Showtimes
        $showtimes = [];
        // Prices based on hall experience
        $experiencePrices = [
            'Standard' => 15.00,
            'IMAX' => 25.00,
            'Dolby Atmos' => 28.00,
            'Gold Class' => 65.00,
        ];

        foreach ($movies as $index => $movie) {
            if (! $movie->is_showing) {
                continue;
            }
            foreach ($halls as $hallIndex => $hall) {
                // Distribute movies across different halls so they don't clash too heavily
                // E.g., Only schedule if index matches hall or some varying offset

                // Creates a showtime for 4 days ahead
                for ($day = 0; $day < 4; $day++) {
                    // Start times at 11 AM, 2 PM, 5 PM, 8 PM based on hall + movie
                    $hour = 11 + (($index + $hallIndex) % 4) * 3;
                    $startTime = now()->addDays($day)->setTime($hour, 0, 0);

                    $price = $experiencePrices[$hall->experience_type] ?? 15.00;
                    $vip_price = $price + 10.00;

                    $showtimes[] = Showtime::firstOrCreate([
                        'movie_id' => $movie->id,
                        'hall_id' => $hall->id,
                        'start_time' => $startTime,
                    ], [
                        'end_time' => $startTime->copy()->addMinutes($movie->duration),
                        'price' => $price,
                        'vip_price' => $vip_price,
                    ]);
                }
            }
        }

        // 5. Create some Bookings and link Seats
        if (Booking::count() == 0 && count($showtimes) > 0) {
            $statuses = ['pending', 'paid', 'cancelled', 'completed'];
            $paymentStatuses = ['pending', 'paid', 'refunded'];

            // Let's create a few more test users for bookings
            $extraUsers = [];
            for ($i = 1; $i <= 5; $i++) {
                $extraUsers[] = User::firstOrCreate(
                    ['email' => "customer{$i}@example.com"],
                    ['name' => "Customer {$i}", 'password' => Hash::make('password'), 'is_admin' => false]
                );
            }
            $allUsers = array_merge([$user], $extraUsers);

            // Generate 20 random bookings
            foreach (range(1, 20) as $i) {
                // Pick random user and random showtime
                $randomUser = $allUsers[array_rand($allUsers)];
                $randomShowtime = $showtimes[array_rand($showtimes)];

                // Pick 1 to 4 random seats from the hall
                $seatsCount = rand(1, 4);
                $availableSeats = $randomShowtime->hall->seats()->inRandomOrder()->take($seatsCount)->get();

                if ($availableSeats->isEmpty()) {
                    continue;
                }

                $totalPrice = 0;
                foreach ($availableSeats as $seat) {
                    $price = $seat->type === 'vip' ? $randomShowtime->vip_price : $randomShowtime->price;
                    $totalPrice += $price;
                }
                $status = $statuses[array_rand($statuses)];
                $paymentStatus = ($status === 'paid' || $status === 'completed') ? 'paid' : $paymentStatuses[array_rand($paymentStatuses)];

                $booking = Booking::create([
                    'user_id' => $randomUser->id,
                    'showtime_id' => $randomShowtime->id,
                    'booking_number' => 'B-'.strtoupper(Str::random(8)),
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'total_seats' => $availableSeats->count(),
                    'total_amount' => $totalPrice,
                ]);

                foreach ($availableSeats as $seat) {
                    $price = $seat->type === 'vip' ? $randomShowtime->vip_price : $randomShowtime->price;
                    $booking->seats()->attach($seat->id, ['price' => $price]);
                }

                // 6. Create Exchange Request for SOME of the paid bookings capriciously
                if ($booking->status === 'paid' && rand(1, 4) === 1) { // 25% chance for paid bookings
                    $otherShowtimes = array_filter($showtimes, fn ($st) => $st->id !== $randomShowtime->id && $st->movie_id === $randomShowtime->movie_id);
                    $newShowtime = empty($otherShowtimes) ? $randomShowtime : $otherShowtimes[array_rand($otherShowtimes)];

                    ExchangeRequest::create([
                        'booking_id' => $booking->id,
                        'user_id' => $randomUser->id,
                        'request_number' => 'REQ-'.strtoupper(Str::random(8)),
                        'new_showtime_id' => $newShowtime->id,
                        'reason' => 'Looking to switch my showtime because of a schedule conflict.',
                        'status' => rand(0, 1) ? 'pending' : 'approved',
                        'selected_seat_ids' => json_encode($availableSeats->pluck('id')->toArray()),
                    ]);
                }
            }
        }
    }
}
