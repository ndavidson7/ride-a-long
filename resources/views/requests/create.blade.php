<x-layouts.main title="Ride listings" :$entries>
    <main class="container-fluid mt-3">
        <form id="request-create" class="" method="post">
            <label class="form-label" for="pickup-checkbox">Pickup</label>
            <input type="checkbox" id="pickup-checkbox" name="pickup-checkbox">
            <div class="autocomplete" id="pickup-div">
                <label class="form-label" for="pickup">Your Pickup Address</label>
                <input type="text" class="place waypoint" name="pickup" id="pickup" />
                <input type="hidden" class="address" id="pickup-address" name="pickup-address" maxlength="255" />
                <input type="hidden" class="latitude" id="pickup-latitude" name="pickup-latitude" />
                <input type="hidden" class="longitude" id="pickup-longitude" name="pickup-longitude" />
            </div>
            <label class="form-label" for="dropoff-checkbox">Dropoff</label>
            <input type="checkbox" id="dropoff-checkbox" name="dropoff-checkbox">
            <div class="autocomplete" id="dropoff-div">
                <label class="form-label" for="dropoff">Your Dropoff Address</label>
                <input type="text" class="place waypoint" name="dropoff" id="dropoff" />
                <input type="hidden" class="address" id="dropoff-address" name="dropoff-address" maxlength="255" />
                <input type="hidden" class="latitude" id="dropoff-latitude" name="dropoff-latitude" />
                <input type="hidden" class="longitude" id="dropoff-longitude" name="dropoff-longitude" />
            </div>
            <button type="submit" class="btn btn-uva-ob">Request</button>
            <button type="button" id="preview-button" class="btn btn-uva-ob" disabled="true" data-bs-toggle="modal"
                data-bs-target="#mapModal" data-ride="{{ $ride->id }}">Preview</button>
        </form>

        <x-modals.map :type="MapType::Request" />
    </main>
</x-layouts.main>
