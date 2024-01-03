<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RideController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RideUserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoutePlannerController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Landing Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->middleware('guest')->name('landing');

/*
|--------------------------------------------------------------------------
| UserController Routes
|--------------------------------------------------------------------------
*/

Route::controller(UserController::class)->name('users.')->group(function () {
    Route::get('/signup', 'create')->middleware('guest')->name('create');
    Route::post('/signup', 'store')->middleware('guest')->name('store');
    Route::get('/profile/edit', 'edit')->middleware(['auth', 'verified'])->name('edit'); // have to put this before show because of wildcard
    Route::get('/profile/{user?}', 'show')->middleware(['auth', 'verified'])->name('show'); // TODO: Could consider changing ID to local-part of email
    Route::put('/profile', 'update')->middleware(['auth', 'verified'])->name('update');
    Route::delete('/profile', 'destroy')->middleware(['auth', 'verified'])->name('destroy');
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

    return redirect()->route('users.edit')->with(['status' => 'success', 'message' => 'Email verified successfully!']);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with(['status' => 'success', 'message' => 'Verification link sent!']);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', function () {
    return view('auth.forgot-password', ['entries' => ['resources/js/form-validation.js']]);
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => 'success', 'message' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware(['guest', 'throttle:2,1'])->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->fill([
                'password' => $password // setPasswordAttribute() mutator will hash the password
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

/*
|--------------------------------------------------------------------------
| RideController Routes
|--------------------------------------------------------------------------
*/

Route::resource('rides', RideController::class)->middleware(['auth', 'verified']);

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
| RoutePlannerController Routes
|--------------------------------------------------------------------------
*/

Route::controller(RoutePlannerController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::post('/optimize', 'optimize')->name('route.optimize');
});

/*
|--------------------------------------------------------------------------
| NotificationController Routes
|--------------------------------------------------------------------------
*/

Route::controller(NotificationController::class)->middleware(['auth', 'verified'])->name('notifications.')->prefix('notifications')->group(function () {
    Route::get('/{notification}', 'show')->name('show');
    Route::delete('/{notification}', 'destroy')->name('destroy');
});

/*
|--------------------------------------------------------------------------
| ConversationController Routes
|--------------------------------------------------------------------------
*/

Route::controller(ConversationController::class)->middleware(['auth', 'verified'])->name('conversations.')->prefix('messages')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/new/{user}', 'create')->name('create');
    Route::post('/new/{user}', 'store')->name('store');
    Route::get('/{conversation}', 'show')->name('show');
    Route::put('/{conversation}', 'update')->name('update');
    Route::delete('/{conversation}', 'destroy')->name('destroy');
});

/*
|--------------------------------------------------------------------------
| SettingsController Routes
|--------------------------------------------------------------------------
*/

Route::controller(SettingsController::class)->middleware(['auth', 'verified'])->name('settings.')->prefix('settings')->group(function () {
    Route::get('/', 'index')->name('index');
    // Route::put('/location', 'update')->name('update');
});
