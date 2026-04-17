@extends('layouts.app')
@section('title', 'Snack Checkout')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h1 class="mb-4">Snack Checkout</h1>
                    <p class="text-muted mb-4">Review your selected snack combos and proceed to payment.</p>

                    <div class="mb-4">
                        <h5>Snack Selection</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($snackData['items'] as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $item['name'] }}</strong>
                                        <div class="small text-muted">{{ $item['description'] }}</div>
                                        <div class="small">Quantity: {{ $item['quantity'] }}</div>
                                    </div>
                                    <span>RM {{ number_format($item['subtotal'], 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total</span>
                            <span class="fs-4 fw-bold text-danger">RM {{ number_format($snackData['total_amount'], 2) }}</span>
                        </div>
                    </div>

                    <form action="{{ route('snacks.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 py-3">Confirm Snack Purchase</button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ route('snacks') }}" class="text-decoration-none">Change snack selection</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
