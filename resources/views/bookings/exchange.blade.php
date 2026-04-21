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
            <p class="text-muted">Choose a new showtime for the same movie and select seats that match your original ticket type.</p>
        </div>
    </div>

    <div class="row gy-4">
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
                    <p class="mb-0">
                        <strong>Seat type requirements:</strong>
                        @foreach($booking->seats->groupBy(fn($seat) => $seat->type ?: 'regular')->map->count() as $type => $count)
                            <span class="badge bg-secondary text-uppercase me-1">{{ $type }} x {{ $count }}</span>
                        @endforeach
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Request New Showtime</h5>
                </div>
                <div class="card-body">
                    @if($showtimes->isEmpty())
                        <div class="alert alert-warning">
                            No future showtimes are available for this movie. Please try again later or choose another booking.
                        </div>
                    @else
                        <form action="{{ route('bookings.request-exchange', $booking->id) }}" method="POST" id="exchange-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="new_showtime_id">Select New Showtime</label>
                                <select name="new_showtime_id" id="new_showtime_id" class="form-select" required>
                                    <option value="">Choose a future showtime</option>
                                    @foreach($showtimes as $showtime)
                                        @php
                                            $availableSeats = $showtime->hall->seats->whereNotIn('id', $showtime->getBookedSeats())->count();
                                        @endphp
                                        <option value="{{ $showtime->id }}" data-available="{{ $availableSeats }}" data-hall="{{ $showtime->hall->name }}">
                                            {{ $showtime->start_time->format('M d, Y h:i A') }} - {{ $showtime->hall->name }} ({{ $availableSeats }} seats available)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4" id="seat-requirement">
                                <div class="alert alert-info mb-0">
                                    Select exactly {{ $booking->total_seats }} seat(s) matching the original seat type mix.
                                </div>
                            </div>

                            <div id="seat-map-wrapper" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Choose Seats</label>
                                    <div id="seat-map" class="border rounded p-3" style="min-height: 280px; background: #f8f9fa;"></div>
                                </div>

                                <div class="mb-3">
                                    <h6>Selected Seats</h6>
                                    <div id="selected-seats-list" class="small text-muted">No seats selected yet.</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-text">Only unbooked seats for the selected showtime are selectable. Seat types must match your original booking.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="reason">Reason for Exchange</label>
                                <textarea name="reason" id="reason" class="form-control" rows="4" required placeholder="Please tell us why you need this exchange."></textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-secondary">Back to Booking</a>
                                <button type="submit" class="btn btn-warning" id="submit-exchange" disabled>Submit Exchange Request</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const exchangeShows = @json($exchangeShows);

    const requiredSeatCount = {{ $booking->total_seats }};
    const requiredTypeCounts = @json($booking->seats->groupBy(fn($seat) => $seat->type ?: 'regular')->map->count()->toArray());
    let selectedSeats = [];
    let activeShowtimeId = null;

    const seatMap = document.getElementById('seat-map');
    const selectedSeatsList = document.getElementById('selected-seats-list');
    const submitButton = document.getElementById('submit-exchange');
    const seatWrapper = document.getElementById('seat-map-wrapper');
    const showtimeSelect = document.getElementById('new_showtime_id');

    showtimeSelect?.addEventListener('change', function () {
        activeShowtimeId = parseInt(this.value, 10);
        selectedSeats = [];
        renderSeatMap();
        updateSelectedSeatsUI();
    });

    function renderSeatMap() {
        seatMap.innerHTML = '';
        if (!activeShowtimeId) {
            seatWrapper.style.display = 'none';
            submitButton.disabled = true;
            return;
        }

        const showtime = exchangeShows.find(item => item.id === activeShowtimeId);
        if (!showtime) {
            seatWrapper.style.display = 'none';
            submitButton.disabled = true;
            return;
        }

        if (showtime.available < requiredSeatCount) {
            seatMap.innerHTML = '<div class="alert alert-warning mb-0">This showtime only has ' + showtime.available + ' seats available. Please choose another showtime.</div>';
            seatWrapper.style.display = 'block';
            submitButton.disabled = true;
            return;
        }

        seatWrapper.style.display = 'block';

        const rows = {};
        showtime.seats.forEach(seat => {
            if (!rows[seat.row]) {
                rows[seat.row] = [];
            }
            rows[seat.row].push(seat);
        });

        Object.keys(rows).sort().forEach(row => {
            const rowContainer = document.createElement('div');
            rowContainer.className = 'd-flex align-items-center mb-2 flex-wrap';
            const rowLabel = document.createElement('div');
            rowLabel.className = 'me-2 fw-bold';
            rowLabel.textContent = row;
            rowContainer.appendChild(rowLabel);

            rows[row].sort((a, b) => a.column - b.column).forEach(seat => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'btn btn-sm me-2 mb-2';
                button.textContent = seat.column;
                button.dataset.seatId = seat.id;
                button.dataset.seatNumber = seat.seat_number;
                button.dataset.type = seat.type;
                button.dataset.row = seat.row;

                if (seat.booked) {
                    button.classList.add('btn-secondary', 'disabled');
                } else {
                    button.classList.add(seat.type === 'vip' ? 'btn-warning' : 'btn-outline-primary');
                    button.addEventListener('click', () => toggleExchangeSeat(seat, button));
                }

                rowContainer.appendChild(button);
            });

            seatMap.appendChild(rowContainer);
        });
    }

    function toggleExchangeSeat(seat, button) {
        const exists = selectedSeats.findIndex(item => item.id === seat.id);
        if (exists >= 0) {
            selectedSeats.splice(exists, 1);
            button.classList.remove('active');
            button.classList.add(seat.type === 'vip' ? 'btn-warning' : 'btn-outline-primary');
        } else {
            if (selectedSeats.length >= requiredSeatCount) {
                return;
            }
            selectedSeats.push(seat);
            button.classList.add('active');
            button.classList.remove(seat.type === 'vip' ? 'btn-warning' : 'btn-outline-primary');
            button.classList.add('btn-success');
        }

        updateSelectedSeatsUI();
    }

    function updateSelectedSeatsUI() {
        const inputs = document.querySelectorAll('input[name="seats[]"]');
        inputs.forEach(input => input.remove());

        if (!selectedSeats.length) {
            selectedSeatsList.innerHTML = '<em>No seats selected yet.</em>';
            submitButton.disabled = true;
            return;
        }

        const seatStrings = selectedSeats.map(seat => seat.seat_number + ' (' + seat.type + ')');
        selectedSeatsList.innerHTML = seatStrings.join(', ');

        selectedSeats.forEach(seat => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'seats[]';
            input.value = seat.id;
            document.getElementById('exchange-form').appendChild(input);
        });

        const seatTypeCounts = selectedSeats.reduce((acc, seat) => {
            acc[seat.type] = (acc[seat.type] || 0) + 1;
            return acc;
        }, {});

        const typeMismatch = Object.keys(requiredTypeCounts).some(type => seatTypeCounts[type] !== requiredTypeCounts[type]);
        submitButton.disabled = selectedSeats.length !== requiredSeatCount || typeMismatch;

        if (typeMismatch) {
            selectedSeatsList.innerHTML += '<div class="text-danger mt-2">Selected seats must match the original seat type counts.</div>';
        }
    }
</script>
@endpush
@endsection
