<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Major;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Jobs\UploadProfilePicture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    public function create()
    {
        return view('auth.signup', [
            'entries' => ['resources/js/form-validation.js']
        ]);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'first-name' => 'required|alpha|max:255', // Might consider some legitimate names invalid?
            'last-name' => 'required|alpha|max:255',
            'email' => 'required|email|ends_with:@virginia.edu|max:255|unique:users,email',
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required|min:8|max:255|confirmed'
        ]);

        $fields = array_combine(
            array_map(function ($key) {
                return str_replace('-', '_', $key);
            }, array_keys($fields)),
            $fields
        );

        $user = User::create($fields);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('rides.index')->with(['status' => 'success', 'message' => 'Account created successfully!']);
    }

    public function show(User $user = null)
    {
        return view('profiles.show', [
            'entries' => ['resources/js/views/profiles/show.js'],
            'user' => $user ?? Auth::user(),
            'uploadedPfp' => session('uploadedPfp') ?? false,
        ]);
    }

    public function edit()
    {
        $user = Auth::user()->load('car');

        return view('profiles.edit', [
            'entries' => ['resources/js/views/profiles/edit.js'],
            'user' => $user,
            'car' => $user->car,
            'majors' => Major::all()
        ]);
    }

    public function update(Request $request)
    {
        $userFields = $request->validate([
            'year' => 'nullable|digits:1|min:1|max:5',
            'major' => 'nullable|numeric|integer|exists:majors,id',
            'bio' => 'nullable|string|max:255',
        ]);

        Auth::user()->update([
            'year' => $userFields['year'],
            'major_id' => $userFields['major'],
            'bio' => $userFields['bio'],
        ]);

        if ($request->has('delete-pfp') && $request['delete-pfp']) {
            Auth::user()->detachMedia(Auth::user()->fetchFirstMedia());
        }

        $request->validate(['pfp' => 'nullable|image|max:2048|dimensions:min_width=200,min_height=200']);
        $hasPfp = $request->hasFile('pfp');
        if ($hasPfp) {
            // temporarily save to storage
            $file = $request->file('pfp');
            $path = $file->storeAs('queuedPfps', Auth::user()->id . "." . $file->extension());
            // dispatch job to upload to cloudinary
            UploadProfilePicture::dispatch(Auth::user(), $path);
        }

        if ($request->has(['car-license-plate', 'car-make', 'car-color'])) {
            $driver = Driver::firstOrNew(['user_id' => Auth::user()->id]);

            $carFields = $request->validate([
                'car-license-plate' => [
                    'nullable', 'required_with:car-make,car-color', 'string', 'alpha_num', 'max:7',
                    Rule::unique('cars', 'license_plate')->ignore($driver->id),
                ],
                'car-make' => 'nullable|required_with:car-license-plate,car-color|string|alpha|max:63',
                'car-color' => 'nullable|required_with:car-license-plate,car-make|string|alpha|max:63',
            ]);

            // Returns default car if none
            $car = $driver->car;
            $car->driver_id = $driver->id;
            $car->license_plate = $carFields['car-license-plate'];
            $car->make = $carFields['car-make'];
            $car->color = $carFields['car-color'];
            $car->save();

            $driver->save();
        }

        return redirect()->route('users.show')->with(['status' => 'success', 'message' => 'Profile updated successfully!', 'uploadedPfp' => $hasPfp]);
    }

    public function destroy()
    {
        Auth::user()->delete();

        return redirect('/')->with(['status' => 'success', 'message' => 'Account deleted successfully!']);
    }
}
