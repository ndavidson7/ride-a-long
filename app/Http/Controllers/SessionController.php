<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('signin', [
            'entries' => ['resources/js/form-enable.js']
        ]);
    }

    public function store()
    {
        $fields = request()->validate([
            'email' => 'required|email|ends_with:@virginia.edu',
            'password' => 'required'
        ]);

        if (!auth()->attempt($fields, request()->has('remember'))) {
            throw ValidationException::withMessages([
                'incorrect' => 'The provided credentials do not match our records.',
            ]);
        }

        request()->session()->regenerate();
        return redirect('/rides')->with('status', 'Signed in successfully!');
    }

    public function destroy()
    {
        auth()->logout();
        return redirect('/signin');
    }
}
