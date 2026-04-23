<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MovieController extends Controller
{
    public function __construct()
    {
        // Authorization is handled globally by EnsureUserIsAdmin middleware,
        // but model-specific Policies are implemented below for demonstration.
    }

    public function index()
    {
        Gate::authorize('viewAny', Movie::class);

        $movies = Movie::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        Gate::authorize('create', Movie::class);

        return view('admin.movies.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Movie::class);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'poster' => 'nullable|url',
            'poster_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'is_showing' => 'boolean',
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
        Gate::authorize('update', $movie);

        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        Gate::authorize('update', $movie);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'poster' => 'nullable|url',
            'poster_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'is_showing' => 'boolean',
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
        Gate::authorize('delete', $movie);

        $movie->delete();

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie deleted successfully.');
    }
}
