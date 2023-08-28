<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create()
    {
        return view('signup', [
            'entries' => ['resources/js/form-enable.js']
        ]);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
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

        Auth::login($user);

        return redirect('/rides')->with('status', 'Account created successfully!');
    }

    public function show(int $id = null)
    {
        $user = $id ? User::findOrFail($id) : Auth::user();

        return view('profiles.show', [
            'entries' => [],
            'user' => $user,
        ]);
    }

    public function edit()
    {
        $user = Auth::user()->load('emergencyContacts', 'car');

        return view('profiles.edit', [
            'entries' => ['resources/js/form-enable.js'],
            'user' => $user,
            'contacts' => $user->emergencyContacts,
            'car' => $user->car,
        ]);
    }

    public function update(Request $request)
    {
        $fields = $request->validate([
            'year' => 'nullable|digits:1|min:1|max:5',
            'major' => 'nullable|string|max:63',
            'bio' => 'nullable|string|max:255',
        ]);

        $nonEmptyFields = array_filter($fields);

        Auth::user()->update($nonEmptyFields);

        return redirect('/profile')->with('status', 'Profile updated successfully!');
    }

    public function destroy()
    {
        Auth::user()->delete();

        return redirect('/')->with('status', 'Account deleted successfully!');
    }
}
