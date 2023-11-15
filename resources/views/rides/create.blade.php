<x-layouts.main title="Create ride" :$entries>
    <main class="py-4">
        <div class="container col-11 col-sm-9 col-md-7 col-lg-6 col-xl-5 col-xxl-4">
            <h2 class="text-center mb-3">Preview</h2>
            <div class="row mb-3">
                <x-map class="d-none" />
            </div>
        </div>
        <form id="ride-create" class="container col-sm-10 col-md-8 col-lg-6" action="{{ route('rides.store') }}"
            method="post">
            @csrf
            <h2 class="text-center mb-3">Ride Details</h2>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            @endif
            <div class="row mb-3">
                <div class="col-sm-8 mb-3 mb-sm-0">
                    <label for="start-time" class="form-label">Date and Time</label>
                    <input type="datetime-local" class="form-control" id="start-time" name="start-time"
                        min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d\TH:i') }}"
                        required />
                </div>
                <div class="col-sm-4">
                    <label for="seats" class="form-label">Seats</label>
                    <input type="number" class="form-control" id="seats" name="seats" min="1" required />
                </div>
            </div>
            <div class="mb-3 autocomplete">
                {{-- ID needed for inputs that supply information to map.js MapComponent --}}
                <label for="origin" class="form-label">Origin</label>
                <input type="text" class="form-control place" id="origin" name="origin" required />
                <input type="hidden" class="address" id="origin-address" name="origin-address" maxlength="255" />
                <input type="hidden" class="city" name="origin-city" />
                <input type="hidden" class="state" name="origin-state" />
                <input type="hidden" class="country" name="origin-country" />
                <input type="hidden" class="latitude" id="origin-latitude" name="origin-latitude" />
                <input type="hidden" class="longitude" id="origin-longitude" name="origin-longitude" />
            </div>
            <div class="mb-3 autocomplete">
                <label for="destination" class="form-label">Destination</label>
                <input type="text" class="form-control place" id="destination" name="destination" required />
                <input type="hidden" class="address" id="destination-address" name="destination-address"
                    maxlength="255" />
                <input type="hidden" class="city" name="destination-city" />
                <input type="hidden" class="state" name="destination-state" />
                <input type="hidden" class="country" name="destination-country" />
                <input type="hidden" class="latitude" id="destination-latitude" name="destination-latitude" />
                <input type="hidden" class="longitude" id="destination-longitude" name="destination-longitude" />
            </div>
            <input type="hidden" name="miles" />
            <div class="form-check mb-3">
                <label class="form-check-label" for="detours-checkbox">Allow <a href="#" data-bs-toggle="tooltip"
                        data-bs-placement="right"
                        data-bs-title="Allowing detours means users can request pickup and/or dropoff locations that are different than your ride's origin and destination">Detours</a></label>
                <input type="checkbox" class="form-check-input" id="detours-checkbox" name="detours" />
            </div>
            {{-- <div class="row mb-3">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" min="0"
                        max="999" step="any" placeholder="00.00" required />
                </div>
                <div class="col mt-auto">
                    <input type="radio" class="btn-check" name="pricing" id="pricing1" value="seat"
                        autocomplete="off" required />
                    <label class="btn btn-uva-ob" for="pricing1">Per Seat</label>
                    <input type="radio" class="btn-check" name="pricing" id="pricing2" value="mile"
                        autocomplete="off" required />
                    <label class="btn btn-uva-ob" for="pricing2">Per Mile</label>
                </div>
            </div> --}}
            <div class="mb-3">
                <label class="form-label" for="description">Description/Additional info</label>
                <textarea class="form-control" id="description" name="description" rows=3 maxlength="255"></textarea>
            </div>
            <button type="submit" class="btn btn-uva-ob">Post</button>
            <button type="button" class="btn btn-uva-ob" id="preview-button" disabled>Preview</button>
        </form>
    </main>
</x-layouts.main>
