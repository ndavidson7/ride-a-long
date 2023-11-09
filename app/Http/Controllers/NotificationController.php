<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return $request->expectsJson() ? Auth::user()->unreadNotifications : null; // TODO: Change nulls to views
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
