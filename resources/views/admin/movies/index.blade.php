@extends('layouts.app')

@section('title', 'Manage Movies')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Manage Movies</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Movie
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Duration</th>
                            <th>Release Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movies as $movie)
                        <tr>
                            <td>{{ $movie->id }}</td>
                            <td>
                                @if($movie->poster)
                                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" style="height: 50px;">
                                @else
                                    <i class="bi bi-film fs-3"></i>
                                @endif
                            </td>
                            <td>{{ $movie->title }}</td>
                            <td>{{ $movie->duration }} min</td>
                            <td>{{ $movie->release_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $movie->is_showing ? 'success' : 'secondary' }}">
                                    {{ $movie->is_showing ? 'Showing' : 'Not Showing' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.movies.edit', $movie) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.movies.destroy', $movie) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $movies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection