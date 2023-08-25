<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        return view('signup');
    }

    public function store()
    {
        $fields = request()->validate([
            'first-name' => 'required|alpha|max:255', // Might consider some legitimate names invalid?
            'last-name' => 'required|alpha|max:255',
            'email' => 'required|email|ends_with:@virginia.edu|max:255|unique:users,email',
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required|same:confirm-password|max:255'
        ]);

        $fields = array_combine(
            array_map(function ($key) {
                return str_replace('-', '_', $key);
            }, array_keys($fields)),
            $fields
        );

        $user = User::create($fields); // TODO: Require email verification

        auth()->login($user);

        return redirect('/rides')->with('status', 'Account created successfully!');
    }
}
