<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'entries' => ['resources/js/views/settings/index.js']
        ]);
    }

    public function updateLocation()
    {
    }
}
