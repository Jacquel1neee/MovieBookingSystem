@extends('layouts.app')

@section('title', 'My Bookings - GSC Cinemas')

@section('content')
<!-- Page Header -->
<div class="container-fluid bg-danger py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <h1 class="text-white fw-bold mb-2">My Bookings</h1>
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
                    <small class="text-muted">Total Bookings</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-success mb-1">
                        {{ $bookings->where('status', 'paid')->count() }}
                    </h3>
                    <small class="text-muted">Active</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-warning mb-1">
                        {{ $bookings->where('status', 'pending')->count() }}
                    </h3>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-secondary mb-1">
                        {{ $bookings->where('status', 'completed')->count() }}
                    </h3>
                    <small class="text-muted">Completed</small>
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
            </ul>
        </div>
    </div>
</div>

<!-- Bookings List -->
<div class="container mb-5">
    <div class="row">
        <div class="col-12">
            @forelse($bookings as $booking)
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Movie Poster (visible on larger screens) -->
                        <div class="col-md-2 d-none d-md-block">
                            @if($booking->showtime->movie->poster)
                                <img src="{{ $booking->showtime->movie->poster }}" class="img-fluid" alt="{{ $booking->showtime->movie->title }}" style="border-radius: 5px;">
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
                            
                            @if($booking->status == 'paid' && $booking->showtime->start_time > now())
                                <button class="btn btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#exchangeModal{{ $booking->id }}">
                                    <i class="bi bi-arrow-repeat me-2"></i>Exchange
                                </button>
                            @endif
                            
                            <!-- QR Code Button -->
                            <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#qrModal{{ $booking->id }}">
                                <i class="bi bi-qr-code me-2"></i>Show QR
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exchange Modal for this booking -->
            <div class="modal fade" id="exchangeModal{{ $booking->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Exchange Tickets</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('bookings.request-exchange', $booking->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Select New Showtime</label>
                                    <select name="new_showtime_id" class="form-select" required>
                                        <option value="">Choose...</option>
                                        @foreach(App\Models\Showtime::where('start_time', '>', now())->get() as $showtime)
                                            <option value="{{ $showtime->id }}">
                                                {{ $showtime->movie->title }} - {{ $showtime->start_time->format('d M h:i A') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reason</label>
                                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning">Submit Request</button>
                            </div>
                        </form>
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
            @empty
            <div class="text-center py-5">
                <i class="bi bi-ticket fs-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">No bookings found</h5>
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