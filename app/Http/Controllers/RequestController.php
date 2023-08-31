<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function create(Ride $ride)
    {
        if (Auth::user()->id === $ride->driver_id) {
            return redirect()->route('rides.index')->with(['status' => 'error', 'message' => 'You cannot request a ride that you are driving.']);
        }

        return view('requests.create', [
            'entries' => ['resources/js/request.js', 'resources/js/google-api.js'],
            'ride' => $ride
        ]);
    }
}
