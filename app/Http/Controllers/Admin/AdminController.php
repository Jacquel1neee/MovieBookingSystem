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