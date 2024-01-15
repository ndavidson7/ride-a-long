<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrUpdateNewRideAlertRequest;
use App\Models\NewRideAlert;
use Illuminate\Support\Facades\Auth;

class NewRideAlertController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(NewRideAlert::class, 'newRideAlert');
    }

    // public function index()
    // {
    //     return view('alerts.new-ride.index', [
    //         'alerts' => Auth::user()->newRideAlerts
    //     ]);
    // }

    public function create()
    {
        return view('alerts.new-ride.create', [
            'entries' => ['resources/js/views/alerts/new-ride/create.js', 'resources/js/form-validation.js']
        ]);
    }

    public function store(StoreOrUpdateNewRideAlertRequest $request)
    {
        NewRideAlert::createFromRequest($request);

        return redirect()->route('alerts.index')->with(['status' => 'success', 'message' => 'New ride alert created.']);
    }

    public function show(NewRideAlert $newRideAlert)
    {
        // TODO: create view
        return view('alerts.new-ride.show', [
            'alert' => $newRideAlert
        ]);
    }

    public function edit(NewRideAlert $newRideAlert)
    {
        return view('alerts.new-ride.edit', [
            'alert' => $newRideAlert,
            'entries' => ['resources/js/views/alerts/new-ride/edit.js', 'resources/js/form-validation.js']
        ]);
    }

    public function update(StoreOrUpdateNewRideAlertRequest $request, NewRideAlert $newRideAlert)
    {
        NewRideAlert::updateFromRequest($request, $newRideAlert);

        return redirect()->route('alerts.index')->with(['status' => 'success', 'message' => 'New ride alert updated.']);
    }

    public function destroy(NewRideAlert $newRideAlert)
    {
        $newRideAlert->delete();

        return redirect()->route('alerts.index')->with(['status' => 'success', 'message' => 'New ride alert deleted.']);
    }
}
