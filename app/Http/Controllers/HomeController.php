<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Get current date
        $today = Carbon::today();

        // Get now showing movies (release date <= today and is_showing = true)
        $nowShowing = Movie::where('is_showing', true)
            ->where('release_date', '<=', $today)
            ->orderBy('release_date', 'desc')
            ->take(6)
            ->get();

        // Get coming soon movies (release date > today)
        $comingSoon = Movie::where('release_date', '>', $today)
            ->orderBy('release_date', 'asc')
            ->take(6)
            ->get();

        // If no coming soon movies, show some random upcoming
        if ($comingSoon->isEmpty()) {
            $comingSoon = Movie::where('is_showing', false)
                ->orderBy('release_date', 'asc')
                ->take(6)
                ->get();
        }

        // Debug: Check if variables are set
        // dd($nowShowing, $comingSoon); // Uncomment to debug

        return view('home', compact('nowShowing', 'comingSoon'));
    }
}
