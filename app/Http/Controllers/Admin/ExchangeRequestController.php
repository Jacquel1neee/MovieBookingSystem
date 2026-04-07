<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeRequest;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Support\Facades\DB;

class ExchangeRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->is_admin) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }
    
    public function index()
    {
        $requests = ExchangeRequest::with(['user', 'booking', 'newShowtime.movie'])
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(15);
        
        return view('admin.exchange-requests.index', compact('requests'));
    }
    
    public function show(ExchangeRequest $exchangeRequest)
    {
        $exchangeRequest->load(['user', 'booking.showtime.movie', 'booking.seats', 'newShowtime.movie', 'newShowtime.hall']);
        return view('admin.exchange-requests.show', compact('exchangeRequest'));
    }
    
    public function approve(Request $request, ExchangeRequest $exchangeRequest)
    {
        $request->validate([
            'admin_remarks' => 'nullable|string'
        ]);
        
        if ($exchangeRequest->status != 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        DB::beginTransaction();
        
        try {
            $oldBooking = $exchangeRequest->booking;
            $newShowtime = $exchangeRequest->newShowtime;
            
            // Check if new showtime is available
            if ($newShowtime->start_time < now()) {
                throw new \Exception('Cannot exchange to a past showtime.');
            }
            
            // Check seat availability for new showtime
            $bookedSeats = $newShowtime->getBookedSeats();
            foreach ($oldBooking->seats as $seat) {
                if (in_array($seat->id, $bookedSeats)) {
                    throw new \Exception('Some seats are no longer available in the new showtime.');
                }
            }
            
            // Create new booking
            $newBooking = Booking::create([
                'booking_number' => 'EX' . strtoupper(uniqid()),
                'user_id' => $exchangeRequest->user_id,
                'showtime_id' => $newShowtime->id,
                'total_seats' => $oldBooking->total_seats,
                'total_amount' => $oldBooking->total_amount,
                'status' => 'paid',
                'payment_status' => 'paid'
            ]);
            
            // Transfer seats to new booking
            foreach ($oldBooking->seats as $seat) {
                BookingSeat::create([
                    'booking_id' => $newBooking->id,
                    'seat_id' => $seat->id,
                    'price' => $newShowtime->price
                ]);
            }
            
            // Cancel old booking
            $oldBooking->status = 'cancelled';
            $oldBooking->payment_status = 'refunded';
            $oldBooking->save();
            
            // Update exchange request
            $exchangeRequest->status = 'approved';
            $exchangeRequest->admin_remarks = $request->admin_remarks;
            $exchangeRequest->save();
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Exchange request approved successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function reject(Request $request, ExchangeRequest $exchangeRequest)
    {
        $request->validate([
            'admin_remarks' => 'required|string'
        ]);
        
        if ($exchangeRequest->status != 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }
        
        $exchangeRequest->status = 'rejected';
        $exchangeRequest->admin_remarks = $request->admin_remarks;
        $exchangeRequest->save();
        
        return redirect()->back()->with('success', 'Exchange request rejected.');
    }
}