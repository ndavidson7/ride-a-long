<x-layouts.main title="Create request" :$entries>
    <main class="py-4">
        <div class="container col-sm-10 col-md-8 col-lg-6 mb-3">
            <h2 class="text-center mb-3">Preview</h2>
            <div class="row">
                <x-map />
            </div>
        </div>
        <form action="{{ route('requests.store', $ride->id) }}" id="request-create" method="post">
            @csrf
            <div class="container col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <h2 class="text-center mb-3">Request Details</h2>
                <p class="text-body-secondary mb-1">All fields are optional</p>
                @if ($ride->detours_allowed)
                    <div class="row mb-3 autocomplete" id="pickup-div">
                        <label class="form-label" for="pickup">Specific pickup location</label>
                        <input type="text" class="form-control place" name="pickup" id="pickup" />
                        <input type="hidden" class="address" id="pickup-address" name="pickup-address"
                            maxlength="255" />
                        <input type="hidden" class="city" name="pickup-city" />
                        <input type="hidden" class="state" name="pickup-state" />
                        <input type="hidden" class="country" name="pickup-country" />
                        <input type="hidden" class="latitude" id="pickup-latitude" name="pickup-latitude" />
                        <input type="hidden" class="longitude" id="pickup-longitude" name="pickup-longitude" />
                    </div>
                @endif
                @if ($ride->detours_allowed)
                    <div class="row mb-3 autocomplete" id="dropoff-div">
                        <label class="form-label" for="dropoff">Specific dropoff location</label>
                        <input type="text" class="form-control place" name="dropoff" id="dropoff" />
                        <input type="hidden" class="address" id="dropoff-address" name="dropoff-address"
                            maxlength="255" />
                        <input type="hidden" class="city" name="dropoff-city" />
                        <input type="hidden" class="state" name="dropoff-state" />
                        <input type="hidden" class="country" name="dropoff-country" />
                        <input type="hidden" class="latitude" id="dropoff-latitude" name="dropoff-latitude" />
                        <input type="hidden" class="longitude" id="dropoff-longitude" name="dropoff-longitude" />
                    </div>
                @endif
                <div class="row mb-3">
                    <label for="message" class="form-label">Leave a message with your request</label>
                    <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                </div>
                <div class="d-flex justify-content-start gap-2">
                    <button type="submit" class="btn btn-primary">Request</button>
                    <button type="button" id="preview-button" class="btn btn-primary" disabled>Preview</button>
                </div>
            </div>
        </form>
        <script>
            var ride = @json($ride);
        </script>
    </main>
</x-layouts.main>
