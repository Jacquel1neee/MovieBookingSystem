@extends('layouts.app')

@section('title', 'Booking Successful')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Booking Successful!</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h4>Thank you for your booking!</h4>
                    <p class="lead">Your booking number is: <strong>{{ $booking->booking_number }}</strong></p>
                    
                    <hr>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Movie Details</h5>
                            <p>
                                <strong>{{ $booking->showtime->movie->title }}</strong><br>
                                {{ $booking->showtime->start_time->format('l, F j, Y') }}<br>
                                {{ $booking->showtime->start_time->format('h:i A') }}<br>
                                {{ $booking->showtime->hall->name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Seat Details</h5>
                            <p>
                                @foreach($booking->seats as $seat)
                                    {{ $seat->seat_number }}@if(!$loop->last), @endif
                                @endforeach
                                <br>
                                Total Amount: <strong>${{ number_format($booking->total_amount, 2) }}</strong>
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('bookings.history') }}" class="btn btn-primary">View Ticket History</a>
                        <a href="{{ route('movies.index') }}" class="btn btn-outline-secondary">Browse More Movies</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection