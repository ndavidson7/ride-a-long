<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Driver;
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

        return redirect()->route('rides.index')->with('status', 'Account created successfully!');
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
            'entries' => ['resources/js/form-enable.js', 'resources/js/emergency-contacts.js'],
            'user' => $user,
            'car' => $user->car,
            'contacts' => $user->emergencyContacts,
        ]);
    }

    public function update(Request $request)
    {
        $userFields = $request->validate([
            'year' => 'nullable|digits:1|min:1|max:5',
            'major' => 'nullable|string|max:63',
            'bio' => 'nullable|string|max:255',
        ]);

        $nonEmptyUserFields = array_filter($userFields);

        if ($nonEmptyUserFields) {
            Auth::user()->update($nonEmptyUserFields);
        }

        $carFields = $request->validate([
            'car-license-plate' => 'nullable|required_with:car-make,car-color|string|alpha_num|max:7|unique:cars,license_plate',
            'car-make' => 'nullable|required_with:car-license-plate,car-color|string|alpha|max:63',
            'car-color' => 'nullable|required_with:car-license-plate,car-make|string|alpha|max:63',
        ]);

        if ($carFields) {
            $driverId = Driver::firstOrCreate(['user_id' => Auth::user()->id])->id;

            // Returns default car if none
            $car = Auth::user()->car;
            $car->driver_id = $driverId;
            $car->license_plate = $carFields['car-license-plate'];
            $car->make = $carFields['car-make'];
            $car->color = $carFields['car-color'];
            $car->save();
        }

        return redirect()->route('profile.show')->with('status', 'Profile updated successfully!');
    }

    public function destroy()
    {
        Auth::user()->delete();

        return redirect('/')->with('status', 'Account deleted successfully!');
    }
}
