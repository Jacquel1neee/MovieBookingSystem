<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRequest;
use Illuminate\Http\Request;

class ExchangeRequestController extends Controller
{
    public function __construct()
    {
        // Authorization is handled by EnsureUserIsAdmin middleware in routes/web.php
    }

    public function index(Request $request)
    {
        $query = ExchangeRequest::with(['user', 'booking', 'newShowtime.movie']);

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.exchange-requests.index', compact('requests'));
    }

    public function show(ExchangeRequest $exchangeRequest)
    {
        $exchangeRequest->load(['user', 'booking.showtime.movie', 'booking.seats', 'newShowtime.movie', 'newShowtime.hall']);

        return view('admin.exchange-requests.show', compact('exchangeRequest'));
    }

    public function updateStatus(Request $request, ExchangeRequest $exchangeRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_remarks' => 'nullable|string',
        ]);

        $exchangeRequest->update([
            'status' => $request->status,
            'admin_remarks' => $request->admin_remarks,
        ]);

        return redirect()->back()->with('success', 'Exchange request status updated successfully.');
    }
}
