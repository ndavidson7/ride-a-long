<x-layouts.app title="Edit ride" :$entries>
    <main class="col-sm-10 col-md-8 col-lg-6 container py-4">
        <h2 class="mb-3 text-center">Preview</h2>
        <div class="row mb-3">
            <x-map />
        </div>
        <form class="row" id="ride-update" action="{{ route('rides.update', $ride) }}" method="post">
            @method('PUT')
            @csrf
            <h2 class="mb-3 text-center">Ride Details</h2>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="start-time">Date and Time</label>
                <input class="form-control" id="start-time" name="start-time" type="datetime-local"
                    value="{{ $ride->start_time }}"
                    min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d\TH:i') }}" required />
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="seats">Seats</label>
                <input class="form-control" id="seats" name="seats" type="number"
                    value="{{ $ride->seats_total }}" min="1" required />
            </div>
            <x-inputs.autocomplete class="col-12 mb-3" id="origin" name="origin" required :address="$ride->origin">
                <x-slot:label>Origin</x-slot:label>
            </x-inputs.autocomplete>
            <x-inputs.autocomplete class="col-12 mb-3" id="destination" name="destination" required :address="$ride->destination">
                <x-slot:label>Destination</x-slot:label>
            </x-inputs.autocomplete>
            <div class="col-12 mb-3">
                <label class="form-label" for="description">Description/Additional info</label>
                <textarea class="form-control" id="description" name="description" rows=3 maxlength="255">{{ $ride->description }}</textarea>
            </div>
        </form>
        <form id="ride-destroy" action="{{ route('rides.destroy', $ride) }}" method="post">
            @method('DELETE')
            @csrf
        </form>
        <div>
            <button class="btn btn-primary" form="ride-update" type="submit">Save</button>
            <button class="btn btn-danger" form="ride-destroy" type="submit">Delete</button>
            <button class="btn btn-primary" id="preview-button" type="button" disabled>Preview</button>
        </div>
        <script>
            var ride = @json($ride);
        </script>
    </main>
</x-layouts.app>
