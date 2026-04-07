@extends('layouts.app')

@section('title', 'Exchange Requests')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Exchange Requests</h1>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Request #</th>
                            <th>User</th>
                            <th>Original Booking</th>
                            <th>Requested Showtime</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                        <tr>
                            <td>{{ $request->request_number }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->booking->booking_number }}</td>
                            <td>
                                @if($request->newShowtime)
                                    {{ $request->newShowtime->movie->title }}<br>
                                    <small>{{ $request->newShowtime->start_time->format('M d, h:i A') }}</small>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ Str::limit($request->reason, 50) }}</td>
                            <td>
                                <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ $request->status }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.exchange-requests.show', $request) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection