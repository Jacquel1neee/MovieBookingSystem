@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('bookings.history') }}">Ticket History</a></li>
                    <li class="breadcrumb-item active">Booking Details</li>
                </ol>
            </nav>
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
                                <img src="{{ $booking->showtime->movie->poster_url }}" class="img-fluid rounded" alt="{{ $booking->showtime->movie->title }}">
                            @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                    <i class="bi bi-film text-white" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $booking->showtime->movie->title }}</h4>
                            <p>
                                <strong>Booking Number:</strong> {{ $booking->booking_number }}<br>
                                <strong>Status:</strong> 
                                <span class="badge bg-{{ $booking->status == 'paid' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($booking->status) }}
                                </span><br>
                                <strong>Payment Status:</strong> 
                                <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($booking->payment_status) }}
                                </span><br>
                                <strong>Date:</strong> {{ $booking->showtime->start_time->format('l, F j, Y') }}<br>
                                <strong>Time:</strong> {{ $booking->showtime->start_time->format('h:i A') }} - {{ $booking->showtime->end_time->format('h:i A') }}<br>
                                <strong>Hall:</strong> {{ $booking->showtime->hall->name }}<br>
                                <strong>Booked on:</strong> {{ $booking->created_at->format('F j, Y h:i A') }}
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
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Total:</th>
                                <th>${{ number_format($booking->total_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    @if($booking->status == 'paid' && $booking->showtime->start_time > now())
                    <div class="mt-4 d-flex flex-wrap gap-2">
                        <a href="{{ route('bookings.exchange', $booking->id) }}" class="btn btn-warning">
                            <i class="bi bi-arrow-repeat"></i> Request Exchange
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="bi bi-x-circle"></i> Cancel Booking
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($booking->exchangeRequests->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5>Exchange Requests</h5>
                </div>
                <div class="card-body">
                    @foreach($booking->exchangeRequests as $request)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>Request #: {{ $request->request_number }}</strong><br>
                                <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                                <p class="mt-2"><strong>Reason:</strong> {{ $request->reason }}</p>
                                @if($request->admin_remarks)
                                <p><strong>Admin Remarks:</strong> {{ $request->admin_remarks }}</p>
                                @endif
                                @if($request->newShowtime)
                                <p><strong>Requested Showtime:</strong> {{ $request->newShowtime->movie->title }} - {{ $request->newShowtime->start_time->format('M d, Y h:i A') }}</p>
                                @endif
                            </div>
                            <div>
                                <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Booking Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Seats:</span>
                            <strong>{{ $booking->total_seats }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <strong>${{ number_format($booking->total_amount, 2) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total:</span>
                            <strong class="text-success">${{ number_format($booking->total_amount, 2) }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exchange Modal -->
<div class="modal fade" id="exchangeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bookings.request-exchange', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Request Ticket Exchange</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_showtime_id" class="form-label">Select New Showtime</label>
                        <select name="new_showtime_id" id="new_showtime_id" class="form-select" required>
                            <option value="">Choose showtime...</option>
                            @foreach(App\Models\Showtime::with('movie')->where('start_time', '>', now())->get() as $showtime)
                                <option value="{{ $showtime->id }}">
                                    {{ $showtime->movie->title }} - {{ $showtime->start_time->format('M d, Y h:i A') }} ({{ $showtime->hall->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Exchange</label>
                        <textarea name="reason" id="reason" rows="3" class="form-control" required placeholder="Please explain why you want to exchange your tickets..."></textarea>
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

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this booking? This action cannot be undone.</p>
                    <p class="text-danger"><strong>Note:</strong> Cancellation is only allowed for future showtimes.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep It</button>
                    <button type="submit" class="btn btn-danger">Yes, Cancel Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection