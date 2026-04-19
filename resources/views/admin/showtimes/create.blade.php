@extends('layouts.app')

@section('title', 'Add Showtime')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Add New Showtime</h1>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.showtimes.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="movie_id" class="form-label">Movie</label>
                    <select class="form-select @error('movie_id') is-invalid @enderror" 
                            id="movie_id" name="movie_id" required>
                        <option value="">Select Movie</option>
                        @foreach($movies as $movie)
                            <option value="{{ $movie->id }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                                {{ $movie->title }} ({{ $movie->duration }} min)
                            </option>
                        @endforeach
                    </select>
                    @error('movie_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="hall_id" class="form-label">Hall</label>
                    <select class="form-select @error('hall_id') is-invalid @enderror" 
                            id="hall_id" name="hall_id" required>
                        <option value="">Select Hall</option>
                        @foreach($halls as $hall)
                            <option value="{{ $hall->id }}" {{ old('hall_id') == $hall->id ? 'selected' : '' }}>
                                {{ $hall->name }} ({{ $hall->total_seats }} seats)
                            </option>
                        @endforeach
                    </select>
                    @error('hall_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                           id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="price" class="form-label">Price ($)</label>
                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                           id="price" name="price" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="vip_price" class="form-label">VIP Price ($)</label>
                    <input type="number" step="0.01" class="form-control @error('vip_price') is-invalid @enderror" 
                           id="vip_price" name="vip_price" value="{{ old('vip_price') }}" required>
                    @error('vip_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.showtimes.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Showtime</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection