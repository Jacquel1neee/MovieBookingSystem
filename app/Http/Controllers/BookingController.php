<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\ExchangeRequest;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getSnackMenu()
    {
        return [
            'popcorn' => [
                'name' => 'Popcorn Combo',
                'description' => 'Large popcorn with two drinks.',
                'price' => 25.00,
                'icon' => 'cup-straw',
                'color' => 'danger',
            ],
            'nachos' => [
                'name' => 'Nachos Combo',
                'description' => 'Crispy nachos with cheese dip and soda.',
                'price' => 18.00,
                'icon' => 'egg-fried',
                'color' => 'warning',
            ],
            'family' => [
                'name' => 'Family Pack',
                'description' => 'Family-sized popcorn, drinks and snacks.',
                'price' => 45.00,
                'icon' => 'shop-window',
                'color' => 'success',
            ],
        ];
    }

    public function selectSeats($showtimeId)
    {
        $showtime = Showtime::with(['movie', 'hall', 'hall.seats'])->findOrFail($showtimeId);
        $bookedSeats = $showtime->getBookedSeats();

        return view('bookings.select-seats', compact('showtime', 'bookedSeats'));
    }

    public function confirmBooking(Request $request)
    {
        $seatIds = $request->input('seats');
        if (is_string($seatIds)) {
            $parsedSeats = json_decode($seatIds, true);
            if (is_array($parsedSeats)) {
                $seatIds = $parsedSeats;
            } else {
                $seatIds = array_filter(explode(',', $seatIds));
            }
            $request->merge(['seats' => $seatIds]);
        }

        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,id',
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
                'total_seats' => count($seats),
            ],
        ]);

        return view('bookings.confirm', compact('showtime', 'seats', 'totalAmount'));
    }

    public function confirmSnacks(Request $request)
    {
        $menu = $this->getSnackMenu();
        $quantities = $request->validate([
            'quantities' => 'required|array',
            'quantities.popcorn' => 'nullable|integer|min:0',
            'quantities.nachos' => 'nullable|integer|min:0',
            'quantities.family' => 'nullable|integer|min:0',
        ])['quantities'];

        $items = [];
        $totalAmount = 0;

        foreach ($menu as $key => $item) {
            $quantity = isset($quantities[$key]) ? (int) $quantities[$key] : 0;
            if ($quantity > 0) {
                $items[] = [
                    'id' => $key,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'quantity' => $quantity,
                    'subtotal' => $item['price'] * $quantity,
                ];
                $totalAmount += $item['price'] * $quantity;
            }
        }

        if (count($items) === 0) {
            return redirect()->back()->with('error', 'Please choose at least one snack combo to continue.');
        }

        session([
            'snack_data' => [
                'items' => $items,
                'total_amount' => $totalAmount,
            ],
        ]);

        if (session()->has('booking_data')) {
            return redirect()->route('bookings.payment')->with('success', 'Snack selection added to your booking.');
        }

        return redirect()->route('snacks.payment');
    }

    public function snacksPayment()
    {
        $snackData = session('snack_data');

        if (! $snackData) {
            return redirect()->route('snacks')->with('error', 'Please select a snack combo first.');
        }

        return view('bookings.snacks-payment', compact('snackData'));
    }

    public function storeSnackOrder(Request $request)
    {
        $snackData = session('snack_data');

        if (! $snackData) {
            return redirect()->route('snacks')->with('error', 'Your snack selection expired. Please choose again.');
        }

        $items = $snackData['items'];
        $totalAmount = $snackData['total_amount'];

        session()->forget('snack_data');

        return view('bookings.snacks-success', compact('items', 'totalAmount'));
    }

    public function paymentPage()
    {
        $bookingData = session('booking_data');

        if (! $bookingData) {
            return redirect()->route('movies.index')->with('error', 'Booking session expired.');
        }

        $showtime = Showtime::with(['movie', 'hall'])->findOrFail($bookingData['showtime_id']);
        $seats = Seat::whereIn('id', $bookingData['seat_ids'])->get();
        $snackData = session('snack_data');
        $combinedTotal = $bookingData['total_amount'] + ($snackData['total_amount'] ?? 0);

        return view('bookings.payment', compact('showtime', 'seats', 'bookingData', 'snackData', 'combinedTotal'));
    }

    public function exchangePage($id)
    {
        $booking = Booking::with(['showtime.movie', 'showtime.hall', 'seats'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $showtimes = Showtime::with(['hall.seats'])
            ->where('movie_id', $booking->showtime->movie_id)
            ->where('start_time', '>', now())
            ->where('id', '!=', $booking->showtime_id)
            ->orderBy('start_time')
            ->get();

        $pageTitle = 'Exchange Ticket';

        return view('bookings.exchange', compact('booking', 'showtimes', 'pageTitle'));
    }

    public function storeBooking(Request $request)
    {
        $bookingData = session('booking_data');

        if (! $bookingData) {
            return redirect()->route('movies.index')->with('error', 'Booking session expired.');
        }

        $showtime = Showtime::findOrFail($bookingData['showtime_id']);
        $bookedSeats = $showtime->getBookedSeats();

        foreach ($bookingData['seat_ids'] as $seatId) {
            if (in_array($seatId, $bookedSeats)) {
                return redirect()->route('movies.index')->with('error', 'Some seats were just booked. Please try again.');
            }
        }

        $snackData = session('snack_data');
        $finalAmount = $bookingData['total_amount'] + ($snackData['total_amount'] ?? 0);

        DB::beginTransaction();

        try {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'showtime_id' => $showtime->id,
                'total_seats' => $bookingData['total_seats'],
                'total_amount' => $finalAmount,
                'status' => 'paid',
                'payment_status' => 'paid',
            ]);

            foreach ($bookingData['seat_ids'] as $seatId) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'price' => $showtime->price,
                ]);
            }

            DB::commit();

            if ($snackData) {
                session(['completed_snack_items' => $snackData['items']]);
            }
            session()->forget('booking_data');
            session()->forget('snack_data');

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
        $completedSnackItems = session('completed_snack_items');
        session()->forget('completed_snack_items');

        return view('bookings.success', compact('booking', 'completedSnackItems'));
    }

    public function myBookings()
    {
        $bookings = Booking::with(['showtime.movie', 'showtime.hall'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pageTitle = 'My Bookings';

        return view('bookings.my-bookings', compact('bookings', 'pageTitle'));
    }

    public function ticketHistory()
    {
        $bookings = Booking::with(['showtime.movie', 'showtime.hall'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $pageTitle = 'Ticket History';

        return view('bookings.my-bookings', compact('bookings', 'pageTitle'));
    }

    public function exchangeDashboard()
    {
        $bookings = Booking::with(['showtime.movie', 'showtime.hall'])
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->whereHas('showtime', function ($query) {
                $query->where('start_time', '>', now());
            })
            ->orderBy('showtime.start_time')
            ->get();

        $pageTitle = 'Exchange Tickets';

        return view('bookings.exchange-dashboard', compact('bookings', 'pageTitle'));
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
        $booking = Booking::with('showtime.movie', 'seats')->where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'new_showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|array',
            'seats.*' => 'exists:seats,id',
            'reason' => 'required|min:10',
        ]);

        if ($booking->status != 'paid') {
            return redirect()->back()->with('error', 'Only paid bookings can be exchanged.');
        }

        if ($booking->showtime->start_time < now()) {
            return redirect()->back()->with('error', 'Cannot exchange past showtimes.');
        }

        if ($booking->exchangeRequests()->where('status', 'pending')->exists()) {
            return redirect()->back()->with('error', 'You already have a pending exchange request for this booking.');
        }

        $newShowtime = Showtime::with('hall.seats')->findOrFail($request->new_showtime_id);

        if ($newShowtime->movie_id !== $booking->showtime->movie_id) {
            return redirect()->back()->with('error', 'Exchange must be for the same movie.');
        }

        if ($newShowtime->id === $booking->showtime_id) {
            return redirect()->back()->with('error', 'Please choose a different showtime to exchange.');
        }

        if (count($request->seats) !== $booking->total_seats) {
            return redirect()->back()->with('error', 'Please select the same number of seats as your original booking.');
        }

        $selectedSeats = Seat::whereIn('id', $request->seats)
            ->where('hall_id', $newShowtime->hall_id)
            ->get();

        if ($selectedSeats->count() !== $booking->total_seats) {
            return redirect()->back()->with('error', 'Please select valid seats for the chosen showtime.');
        }

        $bookedSeats = $newShowtime->getBookedSeats();
        foreach ($selectedSeats as $seat) {
            if (in_array($seat->id, $bookedSeats)) {
                return redirect()->back()->with('error', 'One or more selected seats are no longer available.');
            }
        }

        $originalTypeCounts = $booking->seats->groupBy('type')->map->count()->toArray();
        $selectedTypeCounts = $selectedSeats->groupBy('type')->map->count()->toArray();

        if ($originalTypeCounts !== $selectedTypeCounts) {
            return redirect()->back()->with('error', 'Selected seats must match the original seat type distribution.');
        }

        ExchangeRequest::create([
            'user_id' => Auth::id(),
            'booking_id' => $booking->id,
            'new_showtime_id' => $request->new_showtime_id,
            'selected_seat_ids' => $request->seats,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Exchange request submitted successfully.');
    }
}
