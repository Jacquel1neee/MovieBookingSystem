<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct()
    {
        // Authorization is handled by EnsureUserIsAdmin middleware in routes/web.php
    }

    public function index(Request $request)
    {
        $query = Booking::with(['user', 'showtime.movie', 'seats']);

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('booking_number', 'like', '%'.$request->search.'%');
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'showtime.movie', 'showtime.hall', 'seats']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled,completed',
            'payment_status' => 'required|in:pending,paid,refunded',
        ]);

        $booking->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }
}
