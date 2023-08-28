<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RideController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;
use App\Models\Request;

/*
|--------------------------------------------------------------------------
| Home Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/rides');
})->middleware('auth')->name('home');

/*
|--------------------------------------------------------------------------
| UserController Routes
|--------------------------------------------------------------------------
*/

Route::controller(UserController::class)->group(function () {
    Route::get('/signup', 'create')->middleware('guest')->name('signup');
    Route::post('/signup', 'store')->middleware('guest');
    Route::get('/profile/edit', 'edit')->middleware('auth')->name('profile.edit'); // have to put this before show because of wildcard
    Route::get('/profile/{id?}', 'show')->middleware('auth')->name('profile.show'); // TODO: Could consider changing ID to local-part of email
    Route::put('/profile', 'update')->middleware('auth')->name('profile.update');
    Route::delete('/profile', 'destroy')->middleware('auth')->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| SessionController Routes
|--------------------------------------------------------------------------
*/

Route::controller(SessionController::class)->name('sessions.')->group(function () {
    Route::get('/signin', 'create')->middleware('guest')->name('create');
    Route::post('/signin', 'store')->middleware('guest')->name('store');
    Route::delete('/signout', 'destroy')->middleware('auth')->name('destroy');
});

/*
|--------------------------------------------------------------------------
| RideController Routes
|--------------------------------------------------------------------------
*/

Route::controller(RideController::class)->middleware('auth')->prefix('rides')->name('rides.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store'); // TODO: driver middleware? or just check in controller?
    Route::get('/{ride}', 'show')->name('show');
    Route::get('/{ride}/edit', 'edit')->name('edit');
    Route::put('/{ride}', 'update')->name('update'); // TODO: driver middleware? or just check in controller?
    Route::delete('/{ride}', 'destroy')->name('destroy'); // TODO: driver middleware? or just check in controller?
});

/*
|--------------------------------------------------------------------------
| RequestController Routes
|--------------------------------------------------------------------------
*/

Route::controller(RequestController::class)->middleware('auth')->group(function () {
    Route::get('/requests', 'index');
    Route::get('/rides/{ride}/request', 'create');
    Route::post('/rides/{ride}', 'store');
    Route::get('/requests/{request}', 'show');
    Route::put('/requests/{request}', 'update');
    Route::delete('/requests/{request}', 'destroy');
});

// Route::get('/requests/{request}', [RequestController::class, 'show'])->middleware('auth');

/*
|--------------------------------------------------------------------------
| WaypointController Routes
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| ReportController Routes
|--------------------------------------------------------------------------
*/
