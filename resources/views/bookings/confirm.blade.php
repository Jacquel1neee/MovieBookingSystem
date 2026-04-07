@extends('layouts.app')

@section('title', 'Confirm Booking')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Confirm Your Booking</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($showtime->movie->poster)
                                <img src="{{ $showtime->movie->poster }}" class="img-fluid rounded" alt="{{ $showtime->movie->title }}">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $showtime->movie->title }}</h4>
                            <p>
                                <strong>Date:</strong> {{ $showtime->start_time->format('l, F j, Y') }}<br>
                                <strong>Time:</strong> {{ $showtime->start_time->format('h:i A') }} - {{ $showtime->end_time->format('h:i A') }}<br>
                                <strong>Hall:</strong> {{ $showtime->hall->name }}<br>
                                <strong>Price per seat:</strong> ${{ number_format($showtime->price, 2) }}
                            </p>
                        </div>
                    </div>
                    
                    <h5>Selected Seats</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Seat</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seats as $seat)
                                <tr>
                                    <td>{{ $seat->seat_number }}</td>
                                    <td>{{ ucfirst($seat->type) }}</td>
                                    <td>${{ number_format($showtime->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Total:</th>
                                    <th>${{ number_format($totalAmount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> This is a demo - no actual payment will be processed.
                    </div>
                    
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bookings.select-seats', $showtime->id) }}" class="btn btn-secondary">Back to Seat Selection</a>
                            <button type="submit" class="btn btn-success">Confirm Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection