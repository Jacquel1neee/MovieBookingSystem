@extends('layouts.app')

@section('title', 'Exchange Request - ' . $exchangeRequest->request_number)

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Exchange Request: {{ $exchangeRequest->request_number }}</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Request Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>User:</strong> {{ $exchangeRequest->user->name }}<br>
                            <strong>Email:</strong> {{ $exchangeRequest->user->email }}<br>
                            <strong>Request Date:</strong> {{ $exchangeRequest->created_at ? $exchangeRequest->created_at->format('F j, Y h:i A') : 'N/A' }}<br>
                            <strong>Status:</strong> 
                            <span class="badge bg-{{ $exchangeRequest->status == 'approved' ? 'success' : ($exchangeRequest->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ $exchangeRequest->status }}
                            </span>
                        </div>
                    </div>
                    
                    <h6 class="mt-4">Reason for Exchange:</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $exchangeRequest->reason }}
                    </div>
                    
                    @if($exchangeRequest->admin_remarks)
                    <h6 class="mt-4">Admin Remarks:</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $exchangeRequest->admin_remarks }}
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Original Booking</h5>
                </div>
                <div class="card-body">
                    <h6>Booking #: {{ $exchangeRequest->booking->booking_number }}</h6>
                    <p>
                        <strong>Movie:</strong> {{ $exchangeRequest->booking->showtime->movie->title }}<br>
                        <strong>Date/Time:</strong> {{ $exchangeRequest->booking->showtime->start_time->format('l, F j, Y h:i A') }}<br>
                        <strong>Hall:</strong> {{ $exchangeRequest->booking->showtime->hall->name }}<br>
                        <strong>Seats:</strong> 
                        @foreach($exchangeRequest->booking->seats as $seat)
                            {{ $seat->seat_number }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Requested New Showtime</h5>
                </div>
                <div class="card-body">
                    @if($exchangeRequest->newShowtime)
                        <h6>{{ $exchangeRequest->newShowtime->movie->title }}</h6>
                        <p>
                            <strong>Date/Time:</strong> {{ $exchangeRequest->newShowtime->start_time->format('l, F j, Y h:i A') }}<br>
                            <strong>Hall:</strong> {{ $exchangeRequest->newShowtime->hall->name }}<br>
                            <strong>Price:</strong> ${{ number_format($exchangeRequest->newShowtime->price, 2) }}
                        </p>
                    @else
                        <p class="text-muted">No specific showtime selected</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            @if($exchangeRequest->status == 'pending')
            <div class="card">
                <div class="card-header">
                    <h5>Process Request</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.exchange-requests.approve', $exchangeRequest) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="approve_remarks" class="form-label">Remarks (Optional)</label>
                            <textarea name="admin_remarks" id="approve_remarks" rows="2" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this exchange request?')">
                            Approve Request
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.exchange-requests.reject', $exchangeRequest) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="reject_remarks" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea name="admin_remarks" id="reject_remarks" rows="2" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Reject this exchange request?')">
                            Reject Request
                        </button>
                    </form>
                </div>
            </div>
            @endif
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Quick Info</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-calendar"></i> 
                            <strong>Original Showtime:</strong><br>
                            {{ $exchangeRequest->booking->showtime->start_time->format('M d, Y') }}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-clock"></i>
                            <strong>Days until show:</strong>
                            {{ now()->diffInDays($exchangeRequest->booking->showtime->start_time, false) }} days
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection