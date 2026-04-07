<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Booking;
use App\Models\User;
use App\Models\ExchangeRequest;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->check()) {
                return redirect()->route('login');
            }
            
            if (!auth()->user()->is_admin) {
                abort(403, 'Unauthorized access. Admin only.');
            }
            return $next($request);
        });
    }
    
    public function dashboard()
    {
        $totalMovies = Movie::count();
        $totalBookings = Booking::count();
        $totalUsers = User::count();
        $pendingExchanges = ExchangeRequest::where('status', 'pending')->count();
        
        $recentBookings = Booking::with(['user', 'showtime.movie'])
                                ->orderBy('created_at', 'desc')
                                ->take(10)
                                ->get();
        
        $recentExchanges = ExchangeRequest::with(['user', 'booking'])
                                         ->orderBy('created_at', 'desc')
                                         ->take(10)
                                         ->get();
        
        $revenueToday = Booking::whereDate('created_at', Carbon::today())
                              ->where('payment_status', 'paid')
                              ->sum('total_amount');
        
        return view('admin.dashboard', compact(
            'totalMovies', 'totalBookings', 'totalUsers', 'pendingExchanges',
            'recentBookings', 'recentExchanges', 'revenueToday'
        ));
    }
}