<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function signUp(Request $request)
    {
        $fields = $request->validate([
            'first-name' => 'required|alpha', // Might consider some legitimate names invalid?
            'last-name' => 'required|alpha',
            'email' => 'required|email|ends_with:@virginia.edu|unique:user,email',
            'phone' => 'required|digits:10',
            'password' => 'required|same:password2'
        ]);

        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields); // TODO: Extend user model, require email verification

        return redirect('/signin');
    }

    public function signIn(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|ends_with:@virginia.edu',
            'password' => 'required'
        ]);

        if (auth()->attempt($fields)) {
            $request->session()->regenerate();
            return redirect('/');
        } else {
            return redirect('/signin');
        }
    }

    public function signOut()
    {
        auth()->logout();
        return redirect('/signin');
    }
}
