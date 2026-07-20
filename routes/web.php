<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryPropertyController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\ClientController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| BOOKING PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

Route::get('/cek-booking', function () {
    return Inertia::render('Public/CekBooking');
});

Route::post('/cek-booking', [BookingController::class, 'cek']);
Route::get('/booking-slots', [BookingController::class, 'getSlots']);

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Feature
    |--------------------------------------------------------------------------
    */

    // Category Property
    Route::resource('/categories', CategoryPropertyController::class)
        ->names('categories');
    Route::patch('/categories/{category}/toggle-status', [CategoryPropertyController::class, 'toggleStatus'])
        ->name('categories.toggleStatus');

    // Property
    Route::resource('/properties', PropertyController::class)
        ->names('properties')
        ->except(['create', 'edit', 'show']);

    /*
    |--------------------------------------------------------------------------
    | Admin Booking
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin/bookings')->name('admin.bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::patch('/{booking}/status', [BookingController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('destroy');
    });

    // di dalam group auth
    Route::prefix('admin/clients')->name('admin.clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::patch('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Login, Register, dll)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';