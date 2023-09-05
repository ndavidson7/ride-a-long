<x-layouts.main title="Edit ride" :$entries>
    <main>
        <div class="container-fluid pt-3 col-sm-10 col-md-8 col-lg-6 mx-auto">
            <form id="ride-update" class="row" action="{{ route('rides.update', $ride) }}" method="post">
                @method('PUT')
                @csrf
                <h2 class="text-center col-12">Ride Details</h2>
                <div class="mb-3 col-md-6">
                    <?php date_default_timezone_set('America/New_York'); ?>
                    <label for="start-time" class="form-label">Date and Time</label>
                    <input type="datetime-local" class="form-control" id="start-time" name="start-time"
                        min="{{ date('Y-m-d\TH\:i') }}" value="{{ $ride->start_time }}" required />
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
                <button type="submit" form="ride-update" class="btn btn-uva-ob">Save</button>
                <button type="submit" form="ride-destroy" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </main>
</x-layouts.main>
