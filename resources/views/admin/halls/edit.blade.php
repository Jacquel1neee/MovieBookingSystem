@extends('layouts.app')

@section('title', 'Edit Hall')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Edit Hall: {{ $hall->name }}</h1>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.halls.update', $hall) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Hall Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $hall->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Note: You cannot modify the seat layout after creation. Please manage seats from the seats management page.
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.halls.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Hall</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection