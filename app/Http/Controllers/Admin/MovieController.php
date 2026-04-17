<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->is_admin) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }
    
    public function index()
    {
        $movies = Movie::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.movies.index', compact('movies'));
    }
    
    public function create()
    {
        return view('admin.movies.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'poster' => 'nullable|url',
            'poster_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'is_showing' => 'boolean'
        ]);

        $data = $request->only(['title', 'description', 'duration', 'release_date', 'is_showing']);

        if ($request->hasFile('poster_image')) {
            $data['poster'] = $request->file('poster_image')->store('posters', 'public');
        } elseif ($request->filled('poster')) {
            $data['poster'] = $request->poster;
        }
        
        Movie::create($data);
        
        return redirect()->route('admin.movies.index')
                        ->with('success', 'Movie created successfully.');
    }
    
    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }
    
    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'poster' => 'nullable|url',
            'poster_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'is_showing' => 'boolean'
        ]);

        $data = $request->only(['title', 'description', 'duration', 'release_date', 'is_showing']);

        if ($request->hasFile('poster_image')) {
            $data['poster'] = $request->file('poster_image')->store('posters', 'public');
        } elseif ($request->filled('poster')) {
            $data['poster'] = $request->poster;
        }
        
        $movie->update($data);
        
        return redirect()->route('admin.movies.index')
                        ->with('success', 'Movie updated successfully.');
    }
    
    public function destroy(Movie $movie)
    {
        $movie->delete();
        
        return redirect()->route('admin.movies.index')
                        ->with('success', 'Movie deleted successfully.');
    }
}