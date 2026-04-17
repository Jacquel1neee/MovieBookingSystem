@extends('layouts.app')
@section('title', 'Snack Order Complete')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h1 class="mb-3">Snack Order Complete</h1>
                    <p class="text-muted mb-4">Thank you for your snack purchase! Your snacks will be ready for pickup.</p>

                    <div class="mb-4 text-start">
                        <h5>Order Details</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($items as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $item['name'] }}</strong>
                                        <div class="small text-muted">Quantity: {{ $item['quantity'] }}</div>
                                    </div>
                                    <span>RM {{ number_format($item['subtotal'], 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total Paid</span>
                            <span class="fs-4 fw-bold text-danger">RM {{ number_format($totalAmount, 2) }}</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('movies.index') }}" class="btn btn-primary">Browse Movies</a>
                        <a href="{{ route('snacks') }}" class="btn btn-outline-secondary">Buy More Snacks</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
