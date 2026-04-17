@extends('layouts.app')

@section('title', 'Booking Details - ' . $booking->booking_number)

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Booking Details: {{ $booking->booking_number }}</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Booking Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($booking->showtime->movie->poster)
                                <img src="{{ $booking->showtime->movie->poster }}" class="img-fluid rounded" alt="{{ $booking->showtime->movie->title }}">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $booking->showtime->movie->title }}</h4>
                            <p>
                                <strong>User:</strong> {{ $booking->user->name }} ({{ $booking->user->email }})<br>
                                <strong>Booking Date:</strong> {{ $booking->created_at->format('F j, Y h:i A') }}<br>
                                <strong>Showtime:</strong> {{ $booking->showtime->start_time->format('l, F j, Y h:i A') }}<br>
                                <strong>Hall:</strong> {{ $booking->showtime->hall->name }}<br>
                                <strong>Total Seats:</strong> {{ $booking->total_seats }}<br>
                                <strong>Total Amount:</strong> ${{ number_format($booking->seats->sum('pivot.price'), 2) }}
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="mt-4">Selected Seats</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Seat</th>
                                <th>Type</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->seats as $seat)
                            <tr>
                                <td>{{ $seat->seat_number }}</td>
                                <td>{{ ucfirst($seat->type) }}</td>
                                <td>${{ number_format($seat->pivot->price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Booking Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="pending" {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Booking Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <strong>${{ number_format($booking->seats->sum('pivot.price'), 2) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total:</span>
                            <strong>${{ number_format($booking->seats->sum('pivot.price'), 2) }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection