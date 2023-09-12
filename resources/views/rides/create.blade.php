<x-layouts.main title="Create ride" :$entries>
    <main class="container-fluid mt-3">
        <form id="ride-create" class="row col-sm-10 col-md-8 col-lg-6 mx-auto" action="{{ route('rides.store') }}"
            method="post">
            @csrf
            <h2 class="text-center col-12">Ride Details</h2>
            <div class="mb-3 col-md-6">
                <?php date_default_timezone_set('America/New_York'); ?>
                <label for="start-time" class="form-label">Date and Time</label>
                <input type="datetime-local" class="form-control" id="start-time" name="start-time"
                    min="{{ date('Y-m-d\TH\:i') }}" required />
            </div>
            <div class="mb-3 col-md-6">
                <label for="seats" class="form-label">Seats</label>
                <input type="number" class="form-control" id="seats" name="seats" min="1" required />
            </div>
            <div class="mb-3 col-12 autocomplete">
                <label for="origin" class="form-label">Origin</label>
                <input type="text" class="form-control place" id="origin" name="origin" required />
                <input type="hidden" class="address" id="origin-address" name="origin-address" maxlength="255" />
                <input type="hidden" class="latitude" id="origin-latitude" name="origin-latitude" />
                <input type="hidden" class="longitude" id="origin-longitude" name="origin-longitude" />
            </div>
            <div class="mb-3 col-12 autocomplete">
                <label for="destination" class="form-label">Destination</label>
                <input type="text" class="form-control place" id="destination" name="destination" required />
                <input type="hidden" class="address" id="destination-address" name="destination-address"
                    maxlength="255" />
                <input type="hidden" class="latitude" id="destination-latitude" name="destination-latitude" />
                <input type="hidden" class="longitude" id="destination-longitude" name="destination-longitude" />
            </div>
            <div class="mb-3 col-12">
                <label class="form-label" for="description">Description/Additional info</label>
                <textarea class="form-control" id="description" name="description" rows=3 maxlength="255"></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-uva-ob">Post</button>
                <button type="button" class="btn btn-uva-ob" data-bs-toggle="modal" data-bs-target="#mapModal"
                    data-modal-type="preview">Preview</button>
            </div>
        </form>
    </main>
</x-layouts.main>
