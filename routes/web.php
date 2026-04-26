<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryPropertyController;
use App\Http\Controllers\Admin\PropertyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

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

    // property
    Route::resource('/properties', PropertyController::class)
        ->names('properties')
        ->except(['create', 'edit', 'show']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Login, Register, dll)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';