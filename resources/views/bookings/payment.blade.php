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
                            <p class="mb-1"><strong>Seats:</strong></p>
                            <ul class="mb-1">
                                @foreach($seats as $seat)
                                    <li>{{ $seat->seat_number }} ({{ ucfirst($seat->type) }}) - RM {{ number_format($seat->type === 'vip' ? $showtime->vip_price : $showtime->price, 2) }}</li>
                                @endforeach
                            </ul>
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
                                <span>Subtotal</span>
                                <strong>RM {{ number_format($combinedTotal, 2) }}</strong>
                            </li>
                            <li class="list-group-item">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="promo-code" placeholder="Enter promotion code (e.g., GSCFIRST5)" maxlength="20">
                                    <button class="btn btn-outline-secondary" type="button" id="apply-promo">Apply</button>
                                </div>
                                <small class="text-muted d-block mt-2">Welcome code: GSCFIRST5 for RM5 off</small>
                            </li>
                            @if(session('discount_amount'))
                                <li class="list-group-item d-flex justify-content-between text-success">
                                    <span>Discount</span>
                                    <strong>-RM {{ number_format(session('discount_amount'), 2) }}</strong>
                                </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Payment method</span>
                                <strong>Demo Checkout</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <span class="fw-bold">Total amount</span>
                                <strong class="text-danger">RM {{ number_format($combinedTotal - (session('discount_amount') ?? 0), 2) }}</strong>
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
                            <a href="{{ url()->previous(route('home')) }}" class="btn btn-outline-secondary">Back to Review</a>
                            <button type="submit" class="btn btn-success px-5">Pay Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyPromoBtn = document.getElementById('apply-promo');
    const promoCodeInput = document.getElementById('promo-code');
    
    if (applyPromoBtn && promoCodeInput) {
        applyPromoBtn.addEventListener('click', function() {
            const code = promoCodeInput.value.trim();
            
            if (!code) {
                alert('Please enter a promotion code');
                return;
            }
            
            // Send AJAX request to apply promo code
            fetch("{{ route('bookings.apply-promo') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => Promise.reject(data));
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);
                // Reload the page to show the updated discount
                location.reload();
            })
            .catch(error => {
                alert(error.message || 'Failed to apply promotion code');
                console.error('Error:', error);
            });
        });
    }
});
</script>
@endsection
