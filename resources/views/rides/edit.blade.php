<x-layouts.main title="Edit ride" :$entries>
    <main class="container col-sm-10 col-md-8 col-lg-6 py-4">
        <h2 class="text-center mb-3">Preview</h2>
        <div class="row mb-3">
            <x-map />
        </div>
        <form id="ride-update" class="row" action="{{ route('rides.update', $ride) }}" method="post">
            @method('PUT')
            @csrf
            <h2 class="text-center mb-3">Ride Details</h2>
            <div class="mb-3 col-md-6">
                <label for="start-time" class="form-label">Date and Time</label>
                <input type="datetime-local" class="form-control" id="start-time" name="start-time"
                    min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d\TH:i') }}"
                    value="{{ $ride->start_time }}" required />
            </div>
            <div class="mb-3 col-md-6">
                <label for="seats" class="form-label">Seats</label>
                <input type="number" class="form-control" id="seats" name="seats" min="1"
                    value="{{ $ride->seats_total }}" required />
            </div>
            <div class="mb-3 col-12 autocomplete">
                <label for="origin" class="form-label">Origin</label>
                <input type="text" class="form-control place" id="origin" name="origin"
                    value="{{ $ride->origin->address }}" required />
                <input type="hidden" class="address" id="origin-address" name="origin-address" maxlength="255"
                    value="{{ $ride->origin->address }}" />
                <input type="hidden" class="city" name="origin-city" value="{{ $ride->origin->city }}" />
                <input type="hidden" class="state" name="origin-state" value="{{ $ride->origin->state }}" />
                <input type="hidden" class="country" name="origin-country" value="{{ $ride->origin->country }}" />
                <input type="hidden" class="latitude" id="origin-latitude" name="origin-latitude"
                    value="{{ $ride->origin->latitude }}" />
                <input type="hidden" class="longitude" id="origin-longitude" name="origin-longitude"
                    value="{{ $ride->origin->longitude }}" />
            </div>
            <div class="mb-3 col-12 autocomplete">
                <label for="destination" class="form-label">Destination</label>
                <input type="text" class="form-control place" id="destination" name="destination"
                    value="{{ $ride->destination->address }}" required />
                <input type="hidden" class="address" id="destination-address" name="destination-address"
                    maxlength="255" value="{{ $ride->destination->address }}" />
                <input type="hidden" class="city" name="destination-city" value="{{ $ride->destination->city }}" />
                <input type="hidden" class="state" name="destination-state"
                    value="{{ $ride->destination->state }}" />
                <input type="hidden" class="country" name="destination-country"
                    value="{{ $ride->destination->country }}" />
                <input type="hidden" class="latitude" id="destination-latitude" name="destination-latitude"
                    value="{{ $ride->destination->latitude }}" />
                <input type="hidden" class="longitude" id="destination-longitude" name="destination-longitude"
                    value="{{ $ride->destination->longitude }}" />
            </div>
            <div class="mb-3 col-12">
                <label class="form-label" for="description">Description/Additional info</label>
                <textarea class="form-control" id="description" name="description" rows=3 maxlength="255">{{ $ride->description }}</textarea>
            </div>
        </form>
        <form id="ride-destroy" action="{{ route('rides.destroy', $ride) }}" method="post">
            @method('DELETE')
            @csrf
        </form>
        <div>
            <button type="submit" form="ride-update" class="btn btn-primary">Save</button>
            <button type="submit" form="ride-destroy" class="btn btn-danger">Delete</button>
            <button type="button" class="btn btn-primary" id="preview-button" disabled>Preview</button>
        </div>
        <script>
            var ride = @json($ride);
        </script>
    </main>
</x-layouts.main>
