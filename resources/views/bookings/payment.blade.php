@extends('layouts.app')

@section('title', 'Payment - ' . $showtime->movie->title)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Mock Payment</h5>
                </div>
                <div class="card-body">
                    <p class="mb-4">Review your ticket details and continue to the demonstration payment screen. No real payment will be processed.</p>

                    <div class="row">
                        <div class="col-md-4">
                            @if($showtime->movie->poster)
                                <img src="{{ $showtime->movie->poster_url }}" class="img-fluid rounded" alt="{{ $showtime->movie->title }}">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $showtime->movie->title }}</h4>
                            <p class="mb-1"><strong>Date:</strong> {{ $showtime->start_time->format('l, F j, Y') }}</p>
                            <p class="mb-1"><strong>Time:</strong> {{ $showtime->start_time->format('h:i A') }} - {{ $showtime->end_time->format('h:i A') }}</p>
                            <p class="mb-1"><strong>Hall:</strong> {{ $showtime->hall->name }}</p>
                            <p class="mb-1"><strong>Seats:</strong> {{ implode(', ', $seats->pluck('seat_number')->toArray()) }}</p>
                            <p class="mb-0"><strong>Total:</strong> RM {{ number_format($bookingData['total_amount'], 2) }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h6>Payment Summary</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Seat count</span>
                                <strong>{{ $bookingData['total_seats'] }}</strong>
                            </li>
                            @if($snackData)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Snacks total</span>
                                    <strong>RM {{ number_format($snackData['total_amount'], 2) }}</strong>
                                </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Payment method</span>
                                <strong>Demo Checkout</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total amount</span>
                                <strong>RM {{ number_format($combinedTotal, 2) }}</strong>
                            </li>
                        </ul>
                    </div>

                    @if($snackData)
                        <div class="alert alert-success">
                            <h6 class="mb-2">Selected Snack Combos</h6>
                            <ul class="mb-2">
                                @foreach($snackData['items'] as $item)
                                    <li>{{ $item['quantity'] }} × {{ $item['name'] }} — RM {{ number_format($item['subtotal'], 2) }}</li>
                                @endforeach
                            </ul>
                            <strong>Snack total:</strong> RM {{ number_format($snackData['total_amount'], 2) }}
                        </div>
                    @else
                        <div class="alert alert-secondary">
                            Want snacks with your booking? Visit the <a href="{{ route('snacks') }}" class="link-danger">GSC Snacks</a> page before payment.
                        </div>
                    @endif

                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-between gap-2 flex-wrap">
                            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back();">Back to Review</button>
                            <button type="submit" class="btn btn-success px-5">Pay Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
