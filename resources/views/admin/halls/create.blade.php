@extends('layouts.app')

@section('title', 'Add Hall')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Add New Hall</h1>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.halls.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Hall Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rows" class="form-label">Number of Rows (1-26)</label>
                            <input type="number" class="form-control @error('rows') is-invalid @enderror" 
                                   id="rows" name="rows" min="1" max="26" value="{{ old('rows') }}" required>
                            @error('rows')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="columns" class="form-label">Number of Columns (1-50)</label>
                            <input type="number" class="form-control @error('columns') is-invalid @enderror" 
                                   id="columns" name="columns" min="1" max="50" value="{{ old('columns') }}" required>
                            @error('columns')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Seats will be automatically generated. First 2 rows will be VIP seats.
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.halls.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Hall</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection