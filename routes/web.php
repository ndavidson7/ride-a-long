<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RideController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RideUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Home Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('rides.index');
})->middleware(['auth', 'verified'])->name('home');

/*
|--------------------------------------------------------------------------
| UserController Routes
|--------------------------------------------------------------------------
*/

Route::controller(UserController::class)->group(function () {
    Route::get('/signup', 'create')->middleware('guest')->name('signup');
    Route::post('/signup', 'store')->middleware('guest');
    Route::get('/profile/edit', 'edit')->middleware(['auth', 'verified'])->name('profile.edit'); // have to put this before show because of wildcard
    Route::get('/profile/{id?}', 'show')->middleware(['auth', 'verified'])->name('profile.show'); // TODO: Could consider changing ID to local-part of email
    Route::put('/profile', 'update')->middleware(['auth', 'verified'])->name('profile.update');
    Route::delete('/profile', 'destroy')->middleware(['auth', 'verified'])->name('profile.destroy');
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
| Email Verification Routes
|--------------------------------------------------------------------------
*/

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('profile.edit')->with(['status' => 'success', 'message' => 'Email verified successfully!']);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with(['status' => 'success', 'message' => 'Verification link sent!']);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| RideController Routes
|--------------------------------------------------------------------------
*/

Route::controller(RideController::class)->middleware(['auth', 'verified'])->prefix('rides')->name('rides.')->group(function () {
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

Route::controller(RequestController::class)->middleware(['auth', 'verified'])->name('requests.')->group(function () {
    Route::get('/requests', 'index')->name('index');
    Route::get('/rides/{ride}/request', 'create')->name('create');
    Route::post('/rides/{ride}', 'store')->name('store');
    Route::get('/requests/{request}', 'show')->name('show');
    Route::put('/requests/{request}', 'update')->name('update');
    Route::delete('/requests/{request}', 'destroy')->name('destroy');
});

/*
|--------------------------------------------------------------------------
| RideUserController Routes
|--------------------------------------------------------------------------
*/

Route::controller(RideUserController::class)->middleware(['auth', 'verified'])->name('ride-user.')->group(function () {
    Route::post('/rides/{ride}/users', 'store')->name('store');
    Route::delete('/rides/{ride}/users/{user}', 'destroy')->name('destroy');
});

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
