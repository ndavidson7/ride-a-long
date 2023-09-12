<x-layouts.main title="Create request" :$entries>
    <main>
        <form action="{{ route('requests.store', $ride->id) }}" id="request-create" method="post">
            @csrf
            <div class="container col-sm-8 col-md-6 col-lg-5 col-xl-4 py-5">
                <h2 class="text-center col-12">Request Details</h2>
                <div class="row mb-3">
                    <x-map />
                </div>
                <p class="text-muted mb-1">All fields are optional</p>
                <div class="row mb-3">
                    <div class="autocomplete" id="pickup-div">
                        <label class="form-label" for="pickup">Specific pickup location</label>
                        <input type="text" class="form-control place waypoint" name="pickup" id="pickup" />
                        <input type="hidden" class="address" id="pickup-address" name="pickup-address"
                            maxlength="255" />
                        <input type="hidden" class="latitude" id="pickup-latitude" name="pickup-latitude" />
                        <input type="hidden" class="longitude" id="pickup-longitude" name="pickup-longitude" />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="autocomplete" id="dropoff-div">
                        <label class="form-label" for="dropoff">Specific dropoff location</label>
                        <input type="text" class="form-control place waypoint" name="dropoff" id="dropoff" />
                        <input type="hidden" class="address" id="dropoff-address" name="dropoff-address"
                            maxlength="255" />
                        <input type="hidden" class="latitude" id="dropoff-latitude" name="dropoff-latitude" />
                        <input type="hidden" class="longitude" id="dropoff-longitude" name="dropoff-longitude" />
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="message" class="form-label">Leave a message with your request</label>
                    <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                </div>
                <div class="d-flex justify-content-start gap-2">
                    <button type="submit" class="btn btn-uva-ob">Request</button>
                    <button type="button" id="preview-button" class="btn btn-uva-ob" disabled="true"
                        data-bs-toggle="modal" data-bs-target="#mapModal"
                        data-ride="{{ $ride->id }}">Preview</button>
                </div>
            </div>
        </form>
    </main>
</x-layouts.main>
