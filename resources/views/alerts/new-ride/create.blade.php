<x-layouts.app title="Create new ride alert" :$entries>
    <main>
        <div class="container py-4">
            <h1 class="mb-3">Create new ride alert</h1>

            <h2>Preview</h2>
            <x-map class="d-none mb-3" />

            <h2>Criteria</h2>
            <form action="{{ route('new-ride-alerts.store') }}" method="POST" disabled>
                @csrf
                <div class="row align-items-center mb-3">
                    <x-inputs.autocomplete class="col-md-6 mb-md-0 mb-3" id="origin" name="origin" size="lg"
                        required>
                        <x-slot:label>Origin</x-slot:label>
                        <x-slot:help>Where you would like the ride to begin</x-slot:help>
                    </x-inputs.autocomplete>
                    <div class="col">
                        <label class="form-label" for="origin-radius">Origin radius</label>
                        <input class="form-range" id="origin-radius-slider" type="range" value="0"
                            aria-describedby="origin-radius-slider-help">
                        <div class="form-text" id="origin-radius-slider-help">How far from the origin the ride can start
                        </div>
                    </div>
                    <div class="col-auto">
                        <input class="form-control form-control-lg" id="origin-radius" name="origin-radius"
                            type="number" value="0" min="0" max="100" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-auto">
                        <label class="col-form-label" for="origin-radius">miles</label>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <x-inputs.autocomplete class="col-md-6 mb-md-0 mb-3" id="destination" name="destination"
                        size="lg" required>
                        <x-slot:label>Destination</x-slot:label>
                        <x-slot:help>Where you would like the ride to end</x-slot:help>
                    </x-inputs.autocomplete>
                    <div class="col">
                        <label class="form-label" for="destination-radius">Destination radius</label>
                        <input class="form-range" id="destination-radius-slider" type="range" value="0"
                            aria-describedby="destination-radius-slider-help">
                        <div class="form-text" id="destination-radius-slider-help">How far from the destination the ride
                            can
                            start
                        </div>
                    </div>
                    <div class="col-auto">
                        <input class="form-control form-control-lg" id="destination-radius" name="destination-radius"
                            type="number" value="0" min="0" max="100" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-auto">
                        <label class="col-form-label" for="destination-radius">miles</label>
                    </div>
                </div>

                <div class="row mb-3">
                    <x-inputs.control class="col-md mb-md-0 mb-3" id="start-date" name="start-date" type="date"
                        size="lg"
                        min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d') }}" required>
                        <x-slot:label>Start date</x-slot:label>
                        <x-slot:help>The soonest date you are looking for a ride</x-slot:help>
                    </x-inputs.control>
                    <x-inputs.control class="col-md" id="end-date" name="end-date" type="date" size="lg"
                        min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d') }}" required>
                        <x-slot:label>End date</x-slot:label>
                        <x-slot:help>The latest date you are looking for a ride</x-slot:help>
                    </x-inputs.control>
                </div>

                <button class="btn btn-primary" type="submit">Create</button>
                <button class="btn btn-primary" id="preview-button" type="button" disabled>Preview</button>
            </form>
        </div>
    </main>
</x-layouts.app>
