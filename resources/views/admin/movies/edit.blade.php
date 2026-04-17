 @extends('layouts.app')

@section('title', 'Edit Movie')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Edit Movie: {{ $movie->title }}</h1>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.movies.update', $movie) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $movie->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="5" required>{{ old('description', $movie->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (minutes)</label>
                            <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                   id="duration" name="duration" value="{{ old('duration', $movie->duration) }}" required>
                            @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="release_date" class="form-label">Release Date</label>
                            <input type="date" class="form-control @error('release_date') is-invalid @enderror" 
                                   id="release_date" name="release_date" value="{{ old('release_date', $movie->release_date->format('Y-m-d')) }}" required>
                            @error('release_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="poster" class="form-label">Poster URL</label>
                            <input type="url" class="form-control @error('poster') is-invalid @enderror" 
                                   id="poster" name="poster" value="{{ old('poster', $movie->poster) }}">
                            @error('poster')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="poster_image" class="form-label">Upload Poster</label>
                            <input type="file" class="form-control @error('poster_image') is-invalid @enderror" 
                                   id="poster_image" name="poster_image" accept="image/*">
                            @error('poster_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Upload a local file instead of a URL.</small>
                        </div>
                        @if($movie->poster)
                            <div class="mb-3">
                                <label class="form-label">Current Poster</label>
                                <div>
                                    <img src="{{ $movie->poster_url }}" class="img-fluid rounded" alt="{{ $movie->title }}">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_showing" name="is_showing" value="1" {{ old('is_showing', $movie->is_showing) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_showing">Currently Showing</label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Movie</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection