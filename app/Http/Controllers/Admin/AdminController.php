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
                              
        $revenueThisWeek = Booking::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                              ->where('payment_status', 'paid')
                              ->sum('total_amount');
                              
        $revenueThisMonth = Booking::whereMonth('created_at', Carbon::now()->month)
                               ->whereYear('created_at', Carbon::now()->year)
                               ->where('payment_status', 'paid')
                               ->sum('total_amount');

        // Fetch daily revenue for the current week to show in the graph
        $weeklyRevenue = [];
        $maxRevenue = 0;
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->startOfWeek()->addDays($i);
            $amount = Booking::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
            
            $weeklyRevenue[] = [
                'day' => $date->format('D'),
                'amount' => $amount
            ];
            $maxRevenue = max($maxRevenue, $amount);
        }
        $maxRevenue = $maxRevenue > 0 ? $maxRevenue : 1; // Prevent division by zero

        $todayShowtimes = \App\Models\Showtime::with(['movie', 'hall', 'bookings.seats'])
            ->whereDate('start_time', Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalMovies', 'totalBookings', 'totalUsers', 'pendingExchanges',
            'recentBookings', 'recentExchanges', 'revenueToday', 'revenueThisWeek', 'revenueThisMonth', 'weeklyRevenue', 'maxRevenue', 'todayShowtimes'
        ));
    }

    public function manageAdmins(Request $request)
    {
        $searchUser = $request->input('search_user');
        $searchAdmin = $request->input('search_admin');

        $users = User::where('is_admin', false)
            ->when($searchUser, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10, ['*'], 'users_page')
            ->withQueryString();

        $admins = User::where('is_admin', true)
            ->when($searchAdmin, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10, ['*'], 'admins_page')
            ->withQueryString();

        return view('admin.manage-admins', compact('users', 'admins', 'searchUser', 'searchAdmin'));
    }

    public function toggleAdmin(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot remove your own admin status.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $statusMessage = $user->is_admin ? 'User is now an admin!' : 'Admin rights revoked for user.';
        return redirect()->back()->with('success', $statusMessage);
    }
}