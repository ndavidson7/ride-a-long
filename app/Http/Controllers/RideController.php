<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;

class RideController extends Controller
{
    public function index()
    {
        // return Ride::with(['driver', 'origin_address', 'destination_address'])->get();

        return view('rides.index', [
            'entries' => [],
            'rides' => Ride::with(['driver', 'origin_address', 'destination_address'])->get()
        ]);
    }

    public function create()
    {
        return view('rides.create', [
            'entries' => ['resources/js/google-api.js']
        ]);
    }

    public function store()
    {
    }

    public function show()
    {
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
