<x-layouts.main title="Create new ride alert" :$entries>
    <main>
        <div class="container py-4">
            <h1 class="mb-3">Create new ride alert</h1>

            <h2>Preview</h2>
            <x-map class="d-none mb-3" />

            <h2>Criteria</h2>
            <form action="{{ route('new-ride-alerts.store') }}" method="POST" disabled>
                @csrf
                <div class="row align-items-center mb-3">
                    <x-inputs.autocomplete size="lg" class="col-md-6 mb-3 mb-md-0" name="origin" id="origin"
                        required>
                        <x-slot:label>Origin</x-slot:label>
                        <x-slot:help>Where you would like the ride to begin</x-slot:help>
                    </x-inputs.autocomplete>
                    <div class="col">
                        <label for="origin-radius" class="form-label">Origin radius</label>
                        <input type="range" class="form-range" id="origin-radius-slider" value="0"
                            aria-describedby="origin-radius-slider-help">
                        <div id="origin-radius-slider-help" class="form-text">How far from the origin the ride can start
                        </div>
                    </div>
                    <div class="col-auto">
                        <input type="number" class="form-control form-control-lg" name="origin-radius"
                            id="origin-radius" min="0" max="100" value="0" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-auto">
                        <label for="origin-radius" class="col-form-label">miles</label>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <x-inputs.autocomplete size="lg" class="col-md-6 mb-3 mb-md-0" name="destination"
                        id="destination" required>
                        <x-slot:label>Destination</x-slot:label>
                        <x-slot:help>Where you would like the ride to end</x-slot:help>
                    </x-inputs.autocomplete>
                    <div class="col">
                        <label for="destination-radius" class="form-label">Destination radius</label>
                        <input type="range" class="form-range" id="destination-radius-slider" value="0"
                            aria-describedby="destination-radius-slider-help">
                        <div id="destination-radius-slider-help" class="form-text">How far from the destination the ride
                            can
                            start
                        </div>
                    </div>
                    <div class="col-auto">
                        <input type="number" class="form-control form-control-lg" name="destination-radius"
                            id="destination-radius" min="0" max="100" value="0" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-auto">
                        <label for="destination-radius" class="col-form-label">miles</label>
                    </div>
                </div>

                <div class="row mb-3">
                    <x-inputs.control size="lg" type="date" class="col-md mb-3 mb-md-0" name="start-date"
                        id="start-date"
                        min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d') }}" required>
                        <x-slot:label>Start date</x-slot:label>
                        <x-slot:help>The soonest date you are looking for a ride</x-slot:help>
                    </x-inputs.control>
                    <x-inputs.control size="lg" type="date" class="col-md" name="end-date" id="end-date"
                        min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d') }}" required>
                        <x-slot:label>End date</x-slot:label>
                        <x-slot:help>The latest date you are looking for a ride</x-slot:help>
                    </x-inputs.control>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-primary" id="preview-button" disabled>Preview</button>
            </form>
        </div>
    </main>
</x-layouts.main>
