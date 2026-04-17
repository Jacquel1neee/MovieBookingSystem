@extends('layouts.app')

@section('title', 'Manage Halls')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Manage Halls</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.halls.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Hall
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
                            <th>Name</th>
                            <th>Experience</th>
                            <th>Rows</th>
                            <th>Columns</th>
                            <th>Total Seats</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($halls as $hall)
                        <tr>
                            <td>{{ $hall->id }}</td>
                            <td>{{ $hall->name }}</td>
                            <td>{{ $hall->experience_type ?? 'Standard' }}</td>
                            <td>{{ $hall->rows }}</td>
                            <td>{{ $hall->columns }}</td>
                            <td>{{ $hall->total_seats }}</td>
                            <td>
                                <a href="{{ route('admin.halls.edit', $hall) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.halls.seats', $hall) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-grid"></i>
                                </a>
                                <form action="{{ route('admin.halls.destroy', $hall) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will delete all associated showtimes and seats.')">
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
                {{ $halls->links() }}
            </div>
        </div>
    </div>
</div>
@endsection