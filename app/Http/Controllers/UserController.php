<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function signUp(Request $request) {
        $fields = $request->validate([
            'first-name' => 'required|alpha',
            'last-name' => 'required|alpha',
            'email' => 'required|email|ends_with:@virginia.edu|unique:user,email',
            'phone' => 'required|digits:10',
            'password' => 'required',
            'password2' => 'required|same:password'
        ]);

        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields); // TODO: Extend user model, require email verification

        return redirect('/signin');
    }

    public function signIn(Request $request) {
        $fields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
    }
}
