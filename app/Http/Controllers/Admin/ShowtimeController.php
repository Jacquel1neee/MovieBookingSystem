<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (! auth()->user()->is_admin) {
                abort(403, 'Unauthorized access.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $showtimes = Showtime::with(['movie', 'hall'])
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        return view('admin.showtimes.index', compact('showtimes'));
    }

    public function create()
    {
        $movies = Movie::where('is_showing', true)->get();
        $halls = Hall::all();

        return view('admin.showtimes.create', compact('movies', 'halls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'hall_id' => 'required|exists:halls,id',
            'start_time' => 'required|date',
            'price' => 'required|numeric|min:0',
            'vip_price' => 'required|numeric|min:0',
        ]);

        $movie = Movie::find($request->movie_id);
        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes($movie->duration);

        // Check for conflicts
        $conflictingShowtime = Showtime::where('hall_id', $request->hall_id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })->exists();

        if ($conflictingShowtime) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This hall is already booked for the selected time period.');
        }

        Showtime::create([
            'movie_id' => $request->movie_id,
            'hall_id' => $request->hall_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $request->price,
            'vip_price' => $request->vip_price,
        ]);

        return redirect()->route('admin.showtimes.index')
            ->with('success', 'Showtime created successfully.');
    }

    public function edit(Showtime $showtime)
    {
        $movies = Movie::where('is_showing', true)->get();
        $halls = Hall::all();

        return view('admin.showtimes.edit', compact('showtime', 'movies', 'halls'));
    }

    public function update(Request $request, Showtime $showtime)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'hall_id' => 'required|exists:halls,id',
            'start_time' => 'required|date',
            'price' => 'required|numeric|min:0',
            'vip_price' => 'required|numeric|min:0',
        ]);

        $movie = Movie::find($request->movie_id);
        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addMinutes($movie->duration);

        // Check for conflicts, excluding current showtime
        $conflictingShowtime = Showtime::where('hall_id', $request->hall_id)
            ->where('id', '!=', $showtime->id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })->exists();

        if ($conflictingShowtime) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This hall is already booked for the selected time period.');
        }

        $showtime->update([
            'movie_id' => $request->movie_id,
            'hall_id' => $request->hall_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $request->price,
            'vip_price' => $request->vip_price,
        ]);

        return redirect()->route('admin.showtimes.index')
            ->with('success', 'Showtime updated successfully.');
    }

    public function destroy(Showtime $showtime)
    {
        $showtime->delete();

        return redirect()->route('admin.showtimes.index')
            ->with('success', 'Showtime deleted successfully.');
    }
}
