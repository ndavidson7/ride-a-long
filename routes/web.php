<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RideController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // If the user is not logged in, redirect them to the sign in page.
    if (!auth()->check()) {
        return redirect('/signin');
    }

    return redirect('/rides');
});

// UserController routes
Route::get('/signup', [UserController::class, 'create'])->middleware('guest');
Route::post('/signup', [UserController::class, 'store'])->middleware('guest');
Route::get('/profile', [UserController::class, 'show'])->middleware('auth');

// SessionController routes
Route::get('/signin', [SessionController::class, 'create'])->middleware('guest');
Route::post('/signin', [SessionController::class, 'store'])->middleware('guest');
Route::delete('/signout', [SessionController::class, 'destroy'])->middleware('auth');

// RideController routes
Route::get('/rides', [RideController::class, 'index'])->middleware('auth');
