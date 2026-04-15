@extends('layouts.app')

@section('title', 'Exchange Ticket - ' . $booking->showtime->movie->title)

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('bookings.history') }}">Ticket History</a></li>
                    <li class="breadcrumb-item active">Exchange Ticket</li>
                </ol>
            </nav>
            <h1 class="mb-3">Exchange Ticket</h1>
            <p class="text-muted">Request a new showtime for your booked tickets.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Current Booking</h5>
                </div>
                <div class="card-body">
                    <h4>{{ $booking->showtime->movie->title }}</h4>
                    <p>
                        <strong>Booking #:</strong> {{ $booking->booking_number }}<br>
                        <strong>Showtime:</strong> {{ $booking->showtime->start_time->format('l, F j, Y h:i A') }}<br>
                        <strong>Hall:</strong> {{ $booking->showtime->hall->name }}<br>
                        <strong>Seats:</strong> {{ implode(', ', $booking->seats->pluck('seat_number')->toArray()) }}
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Request New Showtime</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('bookings.request-exchange', $booking->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="new_showtime_id">Select New Showtime</label>
                            <select name="new_showtime_id" id="new_showtime_id" class="form-select" required>
                                <option value="">Choose a future showtime</option>
                                @foreach(App\Models\Showtime::with('movie')->where('start_time', '>', now())->orderBy('start_time')->get() as $showtime)
                                    <option value="{{ $showtime->id }}">
                                        {{ $showtime->movie->title }} - {{ $showtime->start_time->format('M d, Y h:i A') }} ({{ $showtime->hall->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="reason">Reason for Exchange</label>
                            <textarea name="reason" id="reason" class="form-control" rows="4" required placeholder="Please tell us why you need this exchange."></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-secondary">Back to Booking</a>
                            <button type="submit" class="btn btn-warning">Submit Exchange Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
