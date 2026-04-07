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
            'is_showing' => 'boolean'
        ]);
        
        Movie::create($request->all());
        
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
            'is_showing' => 'boolean'
        ]);
        
        $movie->update($request->all());
        
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