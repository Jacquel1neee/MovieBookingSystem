<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();

        if ($request->has('coming-soon')) {
            $query->where('release_date', '>', now()->format('Y-m-d'));
        } else {
            $query->where('is_showing', true)
                  ->where('release_date', '<=', now()->format('Y-m-d'));
        }
        
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('release_date', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('release_date', 'desc');
                    break;
                default:
                    $query->orderBy('release_date', 'desc');
            }
        } else {
            $query->orderBy('release_date', 'desc');
        }
        
        $movies = $query->paginate(12);
        
        return view('movies.index', compact('movies'));
    }
    
    public function show(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $selectedDate = $request->query('date', Carbon::now()->format('Y-m-d'));

        try {
            $selectedDate = Carbon::parse($selectedDate)->format('Y-m-d');
        } catch (\Exception $e) {
            $selectedDate = Carbon::now()->format('Y-m-d');
        }

        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->addDays($i)->format('Y-m-d');
            $showtimes = Showtime::where('movie_id', $movie->id)
                                ->whereDate('start_time', $date)
                                ->with('hall')
                                ->orderBy('start_time')
                                ->get();
            
            $dates[] = [
                'date' => $date,
                'formatted_date' => Carbon::parse($date)->format('l, F j, Y'),
                'showtimes' => $showtimes
            ];
        }
        
        return view('movies.show', compact('movie', 'dates', 'selectedDate'));
    }
}