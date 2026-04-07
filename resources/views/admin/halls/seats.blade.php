@extends('layouts.app')

@section('title', 'Manage Seats - ' . $hall->name)

@push('styles')
<style>
    .seat-grid {
        display: inline-block;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }
    .seat-row {
        display: flex;
        margin-bottom: 5px;
    }
    .seat {
        width: 40px;
        height: 40px;
        margin: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        font-size: 12px;
    }
    .seat.regular {
        background: #fff;
    }
    .seat.vip {
        background: #ffc107;
        border-color: #ffc107;
    }
    .screen {
        background: #6c757d;
        color: #fff;
        text-align: center;
        padding: 10px;
        margin-bottom: 30px;
        border-radius: 5px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Manage Seats - {{ $hall->name }}</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Seat Layout</h5>
                </div>
                <div class="card-body text-center">
                    <div class="screen mb-4">SCREEN</div>
                    
                    <div class="seat-grid">
                        @php
                            $seatsByRow = $seats->groupBy('row')->sortKeys();
                        @endphp
                        
                        @foreach($seatsByRow as $row => $rowSeats)
                        <div class="seat-row">
                            <div class="me-2 fw-bold" style="width: 30px;">{{ chr(64 + $row) }}</div>
                            @foreach($rowSeats->sortBy('column') as $seat)
                            <div class="seat {{ $seat->type }}" title="Seat {{ $seat->seat_number }}">
                                {{ $seat->column }}
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Update Seat Types</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.halls.update-seats', $hall) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Select rows to update:</label>
                            @for($row = 1; $row <= $hall->rows; $row++)
                            <div class="mb-2">
                                <label>Row {{ chr(64 + $row) }}</label>
                                <select name="seat_types[{{ $row }}]" class="form-select">
                                    <option value="regular">Regular</option>
                                    <option value="vip">VIP</option>
                                </select>
                            </div>
                            @endfor
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.halls.index') }}" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">Update Seats</button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="mt-3">
                        <h6>Legend</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="seat regular me-2" style="width: 20px; height: 20px;"></div>
                            <span>Regular Seat</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="seat vip me-2" style="width: 20px; height: 20px;"></div>
                            <span>VIP Seat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection