<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.signin', [
            'entries' => ['resources/js/views/auth/signin.js']
        ]);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|ends_with:@virginia.edu',
            'password' => 'required'
        ]);

        if (!Auth::attempt($fields, $request->has('remember'))) {
            throw ValidationException::withMessages([
                'incorrect' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();
        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Signed in successfully!']);
    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('sessions.create');
    }
}
