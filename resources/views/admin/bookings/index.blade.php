@extends('layouts.app')

@section('title', 'Manage Bookings')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Manage Bookings</h1>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('admin.bookings.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search by booking number..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary">Search</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <select class="form-select w-auto" onchange="window.location.href = this.value">
                            <option value="{{ route('admin.bookings.index', ['status' => 'all']) }}" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="{{ route('admin.bookings.index', ['status' => 'pending']) }}" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="{{ route('admin.bookings.index', ['status' => 'paid']) }}" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="{{ route('admin.bookings.index', ['status' => 'completed']) }}" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking #</th>
                            <th>User</th>
                            <th>Movie</th>
                            <th>Showtime</th>
                            <th>Seats</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_number }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->showtime->movie->title }}</td>
                            <td>{{ $booking->showtime->start_time->format('M d, h:i A') }}</td>
                            <td>{{ $booking->total_seats }}</td>
                            <td>${{ number_format($booking->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status == 'paid' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ $booking->payment_status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection