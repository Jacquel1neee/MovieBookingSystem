@extends('layouts.app')

@section('title', 'Manage Showtimes')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Manage Showtimes</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.showtimes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Showtime
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
                            <th>Movie</th>
                            <th>Hall</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($showtimes as $showtime)
                        <tr>
                            <td>{{ $showtime->id }}</td>
                            <td>{{ $showtime->movie->title }}</td>
                            <td>{{ $showtime->hall->name }}</td>
                            <td>{{ $showtime->start_time->format('M d, Y h:i A') }}</td>
                            <td>{{ $showtime->end_time->format('M d, Y h:i A') }}</td>
                            <td>${{ number_format($showtime->price, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.showtimes.edit', $showtime) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.showtimes.destroy', $showtime) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will affect existing bookings.')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $showtimes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection