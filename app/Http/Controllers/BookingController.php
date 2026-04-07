<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\ExchangeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function selectSeats($showtimeId)
    {
        $showtime = Showtime::with(['movie', 'hall', 'hall.seats'])->findOrFail($showtimeId);
        $bookedSeats = $showtime->getBookedSeats();
        
        return view('bookings.select-seats', compact('showtime', 'bookedSeats'));
    }
    
    public function confirmBooking(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,id'
        ]);
        
        $showtime = Showtime::findOrFail($request->showtime_id);
        $bookedSeats = $showtime->getBookedSeats();
        
        foreach ($request->seats as $seatId) {
            if (in_array($seatId, $bookedSeats)) {
                return redirect()->back()->with('error', 'Some selected seats are no longer available.');
            }
        }
        
        $seats = Seat::whereIn('id', $request->seats)->get();
        $totalAmount = count($seats) * $showtime->price;
        
        session([
            'booking_data' => [
                'showtime_id' => $showtime->id,
                'seat_ids' => $request->seats,
                'total_amount' => $totalAmount,
                'total_seats' => count($seats)
            ]
        ]);
        
        return view('bookings.confirm', compact('showtime', 'seats', 'totalAmount'));
    }
    
    public function storeBooking(Request $request)
    {
        $bookingData = session('booking_data');
        
        if (!$bookingData) {
            return redirect()->route('movies.index')->with('error', 'Booking session expired.');
        }
        
        $showtime = Showtime::findOrFail($bookingData['showtime_id']);
        $bookedSeats = $showtime->getBookedSeats();
        
        foreach ($bookingData['seat_ids'] as $seatId) {
            if (in_array($seatId, $bookedSeats)) {
                return redirect()->route('movies.index')->with('error', 'Some seats were just booked. Please try again.');
            }
        }
        
        DB::beginTransaction();
        
        try {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'showtime_id' => $showtime->id,
                'total_seats' => $bookingData['total_seats'],
                'total_amount' => $bookingData['total_amount'],
                'status' => 'paid',
                'payment_status' => 'paid'
            ]);
            
            foreach ($bookingData['seat_ids'] as $seatId) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'price' => $showtime->price
                ]);
            }
            
            DB::commit();
            
            session()->forget('booking_data');
            
            return redirect()->route('bookings.success', $booking->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }
    
    public function bookingSuccess($id)
    {
        $booking = Booking::with(['showtime.movie', 'showtime.hall', 'seats'])
                         ->where('user_id', Auth::id())
                         ->findOrFail($id);
        
        return view('bookings.success', compact('booking'));
    }
    
    public function myBookings()
    {
        $bookings = Booking::with(['showtime.movie', 'showtime.hall'])
                          ->where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);
        
        return view('bookings.my-bookings', compact('bookings'));
    }
    
    public function showBooking($id)
    {
        $booking = Booking::with(['showtime.movie', 'showtime.hall', 'seats', 'exchangeRequests'])
                         ->where('user_id', Auth::id())
                         ->findOrFail($id);
        
        return view('bookings.show', compact('booking'));
    }
    
    public function cancelBooking($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        
        if ($booking->showtime->start_time < now()) {
            return redirect()->back()->with('error', 'Cannot cancel past showtimes.');
        }
        
        $booking->status = 'cancelled';
        $booking->payment_status = 'refunded';
        $booking->save();
        
        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }
    
    public function requestExchange(Request $request, $id)
    {
        $request->validate([
            'new_showtime_id' => 'required|exists:showtimes,id',
            'reason' => 'required|min:10'
        ]);
        
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        
        if ($booking->status != 'paid') {
            return redirect()->back()->with('error', 'Only paid bookings can be exchanged.');
        }
        
        if ($booking->showtime->start_time < now()) {
            return redirect()->back()->with('error', 'Cannot exchange past showtimes.');
        }
        
        if ($booking->exchangeRequests()->where('status', 'pending')->exists()) {
            return redirect()->back()->with('error', 'You already have a pending exchange request for this booking.');
        }
        
        ExchangeRequest::create([
            'user_id' => Auth::id(),
            'booking_id' => $booking->id,
            'new_showtime_id' => $request->new_showtime_id,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);
        
        return redirect()->back()->with('success', 'Exchange request submitted successfully.');
    }
}