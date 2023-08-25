<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;

class RideController extends Controller
{
    public function index()
    {
        return view('rides.index', [
            'rides' => Ride::all()
        ]);
    }
}
