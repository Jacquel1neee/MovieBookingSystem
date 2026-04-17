@extends('layouts.app')

@section('title', 'Admin Dashboard - GSC Cinemas')

@section('content')
<!-- Page Header -->
<div class="container-fluid bg-danger py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <h1 class="text-white fw-bold mb-2">Admin Dashboard</h1>
                <p class="text-white-50 mb-0">Manage your cinema operations</p>
            </div>
            <div class="col-12 col-md-6 mt-3 mt-md-0">
                <div class="d-flex justify-content-md-end gap-2">
                    <span class="text-white">
                        <i class="bi bi-calendar3 me-2"></i>{{ now()->format('l, d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-film text-danger fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Movies</span>
                            <span class="h3 fw-bold mb-0">{{ $totalMovies ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.movies.index') }}" class="text-danger text-decoration-none small">
                            Manage Movies <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-ticket-perforated text-success fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Bookings</span>
                            <span class="h3 fw-bold mb-0">{{ $totalBookings ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.bookings.index') }}" class="text-success text-decoration-none small">
                            View Bookings <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Total Users</span>
                            <span class="h3 fw-bold mb-0">{{ $totalUsers ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-info text-decoration-none small">
                            Active Users
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                            <i class="bi bi-arrow-repeat text-warning fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Pending Exchanges</span>
                            <span class="h3 fw-bold mb-0">{{ $pendingExchanges ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.exchange-requests.index') }}" class="text-warning text-decoration-none small">
                            Review Requests <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revenue and Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Revenue Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted d-block">Today</small>
                                <span class="h5 fw-bold text-danger">RM {{ number_format($revenueToday ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted d-block">This Week</small>
                                <span class="h5 fw-bold">RM 15,280.50</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-muted d-block">This Month</small>
                                <span class="h5 fw-bold">RM 45,920.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Simple Chart Placeholder -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-end" style="height: 150px;">
                            <div style="width: 12%; height: 60px;" class="bg-danger bg-opacity-25 rounded"></div>
                            <div style="width: 12%; height: 85px;" class="bg-danger bg-opacity-50 rounded"></div>
                            <div style="width: 12%; height: 45px;" class="bg-danger bg-opacity-25 rounded"></div>
                            <div style="width: 12%; height: 110px;" class="bg-danger rounded"></div>
                            <div style="width: 12%; height: 75px;" class="bg-danger bg-opacity-75 rounded"></div>
                            <div style="width: 12%; height: 95px;" class="bg-danger bg-opacity-50 rounded"></div>
                            <div style="width: 12%; height: 65px;" class="bg-danger bg-opacity-25 rounded"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">Mon</small>
                            <small class="text-muted">Tue</small>
                            <small class="text-muted">Wed</small>
                            <small class="text-muted">Thu</small>
                            <small class="text-muted">Fri</small>
                            <small class="text-muted">Sat</small>
                            <small class="text-muted">Sun</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.movies.create') }}" class="btn btn-outline-danger text-start">
                            <i class="bi bi-plus-circle me-2"></i>Add New Movie
                        </a>
                        <a href="{{ route('admin.halls.create') }}" class="btn btn-outline-danger text-start">
                            <i class="bi bi-plus-circle me-2"></i>Add New Hall
                        </a>
                        <a href="{{ route('admin.showtimes.create') }}" class="btn btn-outline-danger text-start">
                            <i class="bi bi-plus-circle me-2"></i>Create Showtime
                        </a>
                        <a href="{{ route('admin.manage-admins') }}" class="btn btn-outline-danger text-start">
                            <i class="bi bi-people me-2"></i>Manage Admin
                        </a>
                        <a href="{{ route('admin.exchange-requests.index') }}" class="btn btn-outline-warning text-start">
                            <i class="bi bi-arrow-repeat me-2"></i>Process Exchanges
                            @if(($pendingExchanges ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $pendingExchanges }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Bookings and Exchange Requests -->
    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Bookings</h5>
                        <a href="{{ route('admin.bookings.index') }}" class="text-danger small">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 py-2">Booking #</th>
                                    <th class="px-3 py-2">Customer</th>
                                    <th class="px-3 py-2">Movie</th>
                                    <th class="px-3 py-2">Amount</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($recentBookings ?? []) as $booking)
                                <tr>
                                    <td class="px-3 py-2">
                                        <small class="fw-bold">{{ $booking->booking_number }}</small>
                                    </td>
                                    <td class="px-3 py-2">
                                        <small>{{ $booking->user->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="px-3 py-2">
                                        <small>{{ $booking->showtime->movie->title ?? 'N/A' }}</small>
                                    </td>
                                    <td class="px-3 py-2">
                                        <small>RM {{ number_format($booking->total_amount ?? 0, 2) }}</small>
                                    </td>
                                    <td class="px-3 py-2">
                                        @if($booking->status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($booking->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <small class="text-muted">No recent bookings</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Exchange Requests</h5>
                        <a href="{{ route('admin.exchange-requests.index') }}" class="text-danger small">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 py-2">Request #</th>
                                    <th class="px-3 py-2">Customer</th>
                                    <th class="px-3 py-2">Booking</th>
                                    <th class="px-3 py-2">Status</th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($recentExchanges ?? []) as $request)
                                <tr>
                                    <td class="px-3 py-2">
                                        <small class="fw-bold">{{ $request->request_number }}</small>
                                    </td>
                                    <td class="px-3 py-2">
                                        <small>{{ $request->user->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="px-3 py-2">
                                        <small>{{ $request->booking->booking_number ?? 'N/A' }}</small>
                                    </td>
                                    <td class="px-3 py-2">
                                        @if($request->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($request->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">
                                        @if($request->status == 'pending')
                                            <a href="{{ route('admin.exchange-requests.show', $request) }}" 
                                               class="btn btn-sm btn-outline-danger">Review</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <small class="text-muted">No exchange requests</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Today's Showtimes -->
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Today's Showtimes</h5>
                        <a href="{{ route('admin.showtimes.index') }}" class="text-danger small">Manage Showtimes</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3">
                                <div class="d-flex gap-3">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded">
                                        <i class="bi bi-film text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Dune: Part Two</h6>
                                        <small class="text-muted d-block">Hall 1 - 10:30 AM</small>
                                        <small class="text-muted">45 seats available</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3">
                                <div class="d-flex gap-3">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded">
                                        <i class="bi bi-film text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Oppenheimer</h6>
                                        <small class="text-muted d-block">Hall 2 - 1:00 PM</small>
                                        <small class="text-muted">28 seats available</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3">
                                <div class="d-flex gap-3">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded">
                                        <i class="bi bi-film text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Poor Things</h6>
                                        <small class="text-muted d-block">Hall 3 - 4:30 PM</small>
                                        <small class="text-muted">52 seats available</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Stats -->
<div class="container-fluid bg-light py-4 mt-5">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3 text-center">
                <span class="text-muted small d-block">Occupancy Rate</span>
                <span class="h5 fw-bold text-danger">78%</span>
            </div>
            <div class="col-6 col-md-3 text-center">
                <span class="text-muted small d-block">Average Ticket Price</span>
                <span class="h5 fw-bold">RM 16.50</span>
            </div>
            <div class="col-6 col-md-3 text-center">
                <span class="text-muted small d-block">Movies Showing</span>
                <span class="h5 fw-bold">{{ $totalMovies ?? 0 }}</span>
            </div>
            <div class="col-6 col-md-3 text-center">
                <span class="text-muted small d-block">Active Halls</span>
                <span class="h5 fw-bold">{{ App\Models\Hall::count() ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>
@endsection