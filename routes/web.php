<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ExchangeRequestController as AdminExchangeRequestController;
use App\Http\Controllers\Admin\HallController as AdminHallController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\ShowtimeController as AdminShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

Route::get('/about-us', [PageController::class, 'about'])->name('about-us');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/terms-of-use', [PageController::class, 'terms'])->name('terms');
Route::get('/promotions', [PageController::class, 'promotions'])->name('promotions');
Route::get('/gsc-snacks', [PageController::class, 'snacks'])->name('snacks');
Route::get('/support/faq', [PageController::class, 'faq'])->name('support.faq');
Route::get('/support/contact', [PageController::class, 'contact'])->name('support.contact');
Route::get('/support/feedback', [PageController::class, 'feedback'])->name('support.feedback');

// Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Protected user routes
Route::middleware(['auth'])->group(function () {
    // Booking routes
    Route::get('/bookings/select-seats/{showtimeId}', [BookingController::class, 'selectSeats'])->name('bookings.select-seats');
    Route::post('/bookings/confirm', [BookingController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::get('/bookings/payment', [BookingController::class, 'paymentPage'])->name('bookings.payment');
    Route::post('/bookings/store', [BookingController::class, 'storeBooking'])->name('bookings.store');
    Route::get('/bookings/success/{id}', [BookingController::class, 'bookingSuccess'])->name('bookings.success');
    Route::post('/snacks/confirm', [BookingController::class, 'confirmSnacks'])->name('snacks.confirm');
    Route::get('/snacks/payment', [BookingController::class, 'snacksPayment'])->name('snacks.payment');
    Route::post('/snacks/store', [BookingController::class, 'storeSnackOrder'])->name('snacks.store');
    Route::get('/ticket-history', [BookingController::class, 'ticketHistory'])->name('bookings.history');
    Route::get('/my-bookings', [BookingController::class, 'ticketHistory'])->name('bookings.my-bookings');
    Route::get('/bookings/exchange', [BookingController::class, 'exchangeDashboard'])->name('bookings.exchange-dashboard');
    Route::get('/bookings/{id}/exchange', [BookingController::class, 'exchangePage'])->name('bookings.exchange');
    Route::get('/bookings/{id}', [BookingController::class, 'showBooking'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::post('/bookings/{id}/exchange', [BookingController::class, 'requestExchange'])->name('bookings.request-exchange');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    // Admin Dashboard - 这个路由必须存在
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/manage-admins', [AdminController::class, 'manageAdmins'])->name('manage-admins');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('toggle-admin');

    // Movie management
    Route::resource('movies', AdminMovieController::class);

    // Hall management
    Route::resource('halls', AdminHallController::class);
    Route::get('/halls/{hall}/seats', [AdminHallController::class, 'seats'])->name('halls.seats');
    Route::post('/halls/{hall}/seats', [AdminHallController::class, 'updateSeats'])->name('halls.update-seats');

    // Showtime management
    Route::resource('showtimes', AdminShowtimeController::class);

    // Booking management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Exchange request management
    Route::get('/exchange-requests', [AdminExchangeRequestController::class, 'index'])->name('exchange-requests.index');
    Route::get('/exchange-requests/{exchangeRequest}', [AdminExchangeRequestController::class, 'show'])->name('exchange-requests.show');
    Route::post('/exchange-requests/{exchangeRequest}/approve', [AdminExchangeRequestController::class, 'approve'])->name('exchange-requests.approve');
    Route::post('/exchange-requests/{exchangeRequest}/reject', [AdminExchangeRequestController::class, 'reject'])->name('exchange-requests.reject');
});
