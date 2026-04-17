@extends('layouts.app')
@section('title', 'GSC Snacks')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>GSC Snacks</h1>
            <p class="lead text-muted">Choose your favorite snack combos and checkout without a movie ticket.</p>
        </div>
    </div>

    @if(session('booking_data'))
        <div class="alert alert-info">
            <strong>Note:</strong> You have an active movie booking in progress. The snack combos you select here will be added to your booking and shown on the payment page.
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('snacks.confirm') }}" method="POST" id="snack-selection-form">
        @csrf
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="text-center mb-3">
                        <i class="bi bi-cup-straw fs-1 text-danger"></i>
                    </div>
                    <h5>Popcorn Combo</h5>
                    <p class="text-muted">Large popcorn with two drinks.</p>
                    <p class="fw-bold">RM 25.00</p>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantities[popcorn]" min="0" value="0" onchange="updateSnackTotal()">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="text-center mb-3">
                        <i class="bi bi-egg-fried fs-1 text-warning"></i>
                    </div>
                    <h5>Nachos Combo</h5>
                    <p class="text-muted">Crispy nachos with cheese dip and soda.</p>
                    <p class="fw-bold">RM 18.00</p>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantities[nachos]" min="0" value="0" onchange="updateSnackTotal()">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="text-center mb-3">
                        <i class="bi bi-shop-window fs-1 text-success"></i>
                    </div>
                    <h5>Family Pack</h5>
                    <p class="text-muted">Family-sized popcorn, drinks and snacks.</p>
                    <p class="fw-bold">RM 45.00</p>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantities[family]" min="0" value="0" onchange="updateSnackTotal()">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card p-4 border-0 shadow-sm">
                    <h5 class="mb-3">Order Summary</h5>
                    <p class="mb-1">Select quantities for one or more combos. If you have a ticket booking open, snacks will attach to that booking.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Estimated Total</span>
                        <span class="fs-4 fw-bold text-danger" id="snack-total">RM 0.00</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                @auth
                    <button type="submit" class="btn btn-danger w-100 py-3">Continue</button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-danger w-100 py-3">Login to Buy Snacks</a>
                @endauth
            </div>
        </div>
    </form>
</div>

<script>
    const snacks = {
        popcorn: 25.00,
        nachos: 18.00,
        family: 45.00,
    };

    function updateSnackTotal() {
        let total = 0;
        for (const key in snacks) {
            const input = document.querySelector(`[name="quantities[${key}]"]`);
            if (!input) continue;
            const quantity = parseInt(input.value, 10) || 0;
            total += snacks[key] * quantity;
        }
        document.getElementById('snack-total').textContent = 'RM ' + total.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', updateSnackTotal);
</script           <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="text-center mb-3">
                        <i class="bi bi-egg-fried fs-1 text-warning"></i>
                    </div>
                    <h5>Nachos Combo</h5>
                    <p class="text-muted">Crispy nachos with cheese dip and soda.</p>
                    <p class="fw-bold">RM 18.00</p>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantities[nachos]" min="0" value="0" onchange="updateSnackTotal()">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="text-center mb-3">
                        <i class="bi bi-shop-window fs-1 text-success"></i>
                    </div>
                    <h5>Family Pack</h5>
                    <p class="text-muted">Family-sized popcorn, drinks and snacks.</p>
                    <p class="fw-bold">RM 45.00</p>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantities[family]" min="0" value="0" onchange="updateSnackTotal()">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card p-4 border-0 shadow-sm">
                    <h5 class="mb-3">Order Summary</h5>
                    <p class="mb-1">Select quantities for one or more combos. If you have a ticket booking open, snacks will attach to that booking.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Estimated Total</span>
                        <span class="fs-4 fw-bold text-danger" id="snack-total">RM 0.00</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                @auth
                    <button type="submit" class="btn btn-danger w-100 py-3">Continue</button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-danger w-100 py-3">Login to Buy Snacks</a>
                @endauth
            </div>
        </div>
    </form>
</div>

<script>
    const snacks = {
        popcorn: 25.00,
        nachos: 18.00,
        family: 45.00,
    };

    function updateSnackTotal() {
        let total = 0;
        for (const key in snacks) {
            const input = document.querySelector(`[name="quantities[${key}]"]`);
            if (!input) continue;
            const quantity = parseInt(input.value, 10) || 0;
            total += snacks[key] * quantity;
        }
        document.getElementById('snack-total').textContent = 'RM ' + total.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', updateSnackTotal);
</script>
@endsection
