<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return 'Test';
});
Route::get('/signin', function () {
    // return view('signin', ['withNavbar' => false]);
    return view('signin');
});
Route::get('/signup', function () {
    return view('signup');
});

Route::post('/signin', [UserController::class, 'signIn']);
Route::post('/signup', [UserController::class, 'signUp']);
Route::post('/signout', [UserController::class, 'signOut']);
