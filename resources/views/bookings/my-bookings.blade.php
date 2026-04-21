@extends('layouts.app')

@section('title', ($pageTitle ?? 'My Bookings') . ' - GSC Cinemas')

@section('content')
<!-- Page Header -->
<div class="container-fluid bg-danger py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <h1 class="text-white fw-bold mb-2">{{ $pageTitle ?? 'My Bookings' }}</h1>
                <p class="text-white-50 mb-0">View and manage your movie tickets</p>
            </div>
        </div>
    </div>
</div>

<!-- Booking Stats -->
<div class="container mb-4">
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-danger mb-1">{{ $bookings->total() }}</h3>
                    <small class="text-muted">Total Items</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-success mb-1">
                        @php
                            $activeCount = 0;
                            foreach($bookings->getCollection() as $item) {
                                if ((is_array($item) ? $item['type'] === 'booking' && $item['status'] === 'paid' : (isset($item->type) && $item->type === 'booking' && $item->status === 'paid'))) {
                                    $activeCount++;
                                }
                            }
                            echo $activeCount;
                        @endphp
                    </h3>
                    <small class="text-muted">Active Bookings</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-warning mb-1">
                        @php
                            $pendingCount = 0;
                            foreach($bookings->getCollection() as $item) {
                                if ((is_array($item) ? $item['type'] === 'booking' && $item['status'] === 'pending' : (isset($item->type) && $item->type === 'booking' && $item->status === 'pending'))) {
                                    $pendingCount++;
                                }
                            }
                            echo $pendingCount;
                        @endphp
                    </h3>
                    <small class="text-muted">Pending Bookings</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-secondary mb-1">
                        @php
                            $snackCount = 0;
                            foreach($bookings->getCollection() as $item) {
                                if ((is_array($item) ? $item['type'] === 'snack_only' : (isset($item->type) && $item->type === 'snack_only'))) {
                                    $snackCount++;
                                }
                            }
                            echo $snackCount;
                        @endphp
                    </h3>
                    <small class="text-muted">Snack Orders</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="container mb-4">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <a class="nav-link {{ !request('status') ? 'active bg-danger text-white' : 'text-dark' }}" 
                       href="{{ route('bookings.my-bookings') }}">
                        All
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'paid' ? 'active bg-danger text-white' : 'text-dark' }}" 
                       href="{{ route('bookings.my-bookings', ['status' => 'paid']) }}">
                        Active
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'completed' ? 'active bg-danger text-white' : 'text-dark' }}" 
                       href="{{ route('bookings.my-bookings', ['status' => 'completed']) }}">
                        Completed
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'cancelled' ? 'active bg-danger text-white' : 'text-dark' }}" 
                       href="{{ route('bookings.my-bookings', ['status' => 'cancelled']) }}">
                        Cancelled
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') == 'snacks_only' ? 'active bg-danger text-white' : 'text-dark' }}" 
                       href="{{ route('bookings.my-bookings', ['status' => 'snacks_only']) }}">
                        Snacks Only
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Bookings List -->
<div class="container mb-5">
    <div class="row">
        <div class="col-12">
            @forelse($bookings as $item)
            @if($item->type === 'booking')
                @php
                    $booking = $item;
                    $rowClass = 'border-0 shadow-sm';
                    if ($booking->status == 'paid') {
                        $rowClass = 'border-success shadow-sm';
                    } elseif ($booking->status == 'completed') {
                        $rowClass = 'border-secondary shadow-sm';
                    } elseif ($booking->status == 'cancelled') {
                        $rowClass = 'border-danger shadow-sm';
                    }
                @endphp
                <div class="card mb-3 {{ $rowClass }}">
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Movie Poster (visible on larger screens) -->
                            <div class="col-md-2 d-none d-md-block">
                                @if($booking->showtime->movie->poster)
                                    <img src="{{ $booking->showtime->movie->poster_url }}" class="img-fluid" alt="{{ $booking->showtime->movie->title }}" style="border-radius: 5px;">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 120px;">
                                        <i class="bi bi-film text-white"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Booking Details -->
                            <div class="col-12 col-md-7">
                                <!-- Movie Title and Booking Number -->
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <h5 class="fw-bold mb-0">{{ $booking->showtime->movie->title }}</h5>
                                    <span class="badge bg-light text-dark">#{{ $booking->booking_number }}</span>
                                    @if($booking->snackOrders->count() > 0)
                                        <span class="badge bg-info text-white">
                                            <i class="bi bi-cup-straw"></i> {{ $booking->snackOrders->count() }} snack order(s)
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Details Grid -->
                                <div class="row g-2 mb-2">
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Date</small>
                                        <span>{{ $booking->showtime->start_time->format('d M Y') }}</span>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Time</small>
                                        <span>{{ $booking->showtime->start_time->format('h:i A') }}</span>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Hall</small>
                                        <span>{{ $booking->showtime->hall->name }}</span>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Seats</small>
                                        <span>{{ $booking->total_seats }} seat(s)</span>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Amount</small>
                                        <span class="text-danger fw-bold">RM {{ number_format($booking->total_amount, 2) }}</span>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Status</small>
                                        @if($booking->status == 'paid')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge bg-secondary">Completed</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="col-12 col-md-3 d-flex flex-column gap-2">
                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-eye me-2"></i>View Details
                                </a>
                                
                                <!-- QR Code Button -->
                                <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#qrModal{{ $booking->id }}">
                                    <i class="bi bi-qr-code me-2"></i>Show QR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Modal -->
                <div class="modal fade" id="qrModal{{ $booking->id }}" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Your Ticket QR</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="bg-light p-4 mb-3">
                                    <i class="bi bi-qr-code" style="font-size: 200px;"></i>
                                </div>
                                <p class="small text-muted">Show this QR code at the cinema entrance</p>
                                <p class="fw-bold mb-0">{{ $booking->booking_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @php
                    $snackOrder = $item;
                @endphp
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Snack Icon -->
                            <div class="col-md-2 d-none d-md-block">
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 120px; border-radius: 5px;">
                                    <i class="bi bi-cup-straw text-danger" style="font-size: 40px;"></i>
                                </div>
                            </div>
                            
                            <!-- Snack Order Details -->
                            <div class="col-12 col-md-7">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <h5 class="fw-bold mb-0">Snack Order</h5>
                                    <span class="badge bg-light text-dark">#{{ $snackOrder->order_number }}</span>
                                    <span class="badge bg-info text-white">
                                        <i class="bi bi-cup-straw"></i> {{ count($snackOrder->items) }} item(s)
                                    </span>
                                </div>
                                
                                <!-- Details Grid -->
                                <div class="row g-2 mb-2">
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Date</small>
                                        <span>{{ $snackOrder->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Time</small>
                                        <span>{{ $snackOrder->created_at->format('h:i A') }}</span>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <small class="text-muted d-block">Items</small>
                                        <div class="small">
                                            @foreach($snackOrder->items as $snackItem)
                                                <div>{{ $snackItem['quantity'] }}× {{ $snackItem['name'] }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Amount</small>
                                        <span class="text-danger fw-bold">RM {{ number_format($snackOrder->total_amount, 2) }}</span>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <small class="text-muted d-block">Status</small>
                                        @if($snackOrder->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-warning">{{ ucfirst($snackOrder->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @empty
            <div class="text-center py-5">
                <i class="bi bi-ticket fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No bookings or snack orders found</h5>
                <p class="text-muted">Start booking your favorite movies now!</p>
                <a href="{{ route('movies.index') }}" class="btn btn-danger mt-3">Browse Movies</a>
            </div>
            @endforelse
            
            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection