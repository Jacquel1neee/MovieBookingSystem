@extends('layouts.app')

@section('title', ($pageTitle ?? 'Exchange Tickets') . ' - GSC Cinemas')

@section('content')
<div class="container-fluid bg-danger py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <h1 class="text-white fw-bold mb-2">{{ $pageTitle ?? 'Exchange Tickets' }}</h1>
                <p class="text-white-50 mb-0">Choose an upcoming booking and request a new showtime.</p>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    @if($bookings->count())
        <div class="row g-3">
            @foreach($bookings as $booking)
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center gy-3">
                                <div class="col-md-8">
                                    <h5 class="fw-bold mb-2">{{ $booking->showtime->movie->title }}</h5>
                                    <div class="row g-2">
                                        <div class="col-sm-4">
                                            <small class="text-muted d-block">Booking #</small>
                                            <span>#{{ $booking->booking_number }}</span>
                                        </div>
                                        <div class="col-sm-4">
                                            <small class="text-muted d-block">Showtime</small>
                                            <span>{{ $booking->showtime->start_time->format('d M Y, h:i A') }}</span>
                                        </div>
                                        <div class="col-sm-4">
                                            <small class="text-muted d-block">Seats</small>
                                            <span>{{ $booking->total_seats }} seat(s)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 d-grid gap-2">
                                    <a href="{{ route('bookings.exchange', $booking->id) }}" class="btn btn-warning">
                                        <i class="bi bi-arrow-repeat me-2"></i>Request Exchange
                                    </a>
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-eye me-2"></i>View Booking
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-arrow-repeat fs-1 text-warning d-block mb-3"></i>
            <h4 class="fw-bold">No exchangeable bookings found</h4>
            <p class="text-muted">You can only exchange paid bookings with a future showtime.</p>
            <div class="d-flex justify-content-center gap-2 mt-4 flex-wrap">
                <a href="{{ route('bookings.history') }}" class="btn btn-danger">View Ticket History</a>
                <a href="{{ route('movies.index') }}" class="btn btn-outline-secondary">Browse Movies</a>
            </div>
        </div>
    @endif
</div>
@endsection
