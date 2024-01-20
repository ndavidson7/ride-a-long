<x-layouts.app title="Create request" :$entries>
    <main class="py-4">
        <div class="col-sm-10 col-md-8 col-lg-6 container mb-3">
            <h2 class="mb-3 text-center">Preview</h2>
            <div class="row">
                <x-map />
            </div>
        </div>
        <form id="request-create" action="{{ route('requests.store', $ride->id) }}" method="post">
            @csrf
            <div class="col-sm-8 col-md-6 col-lg-5 col-xl-4 container">
                <h2 class="mb-3 text-center">Request Details</h2>
                <p class="text-body-secondary mb-1">All fields are optional</p>
                @if ($ride->detours_allowed)
                    <x-inputs.autocomplete class="row mb-3" id="pickup" name="pickup">
                        <x-slot:label>Specific pickup location</x-slot:label>
                    </x-inputs.autocomplete>
                @endif
                @if ($ride->detours_allowed)
                    <x-inputs.autocomplete class="row mb-3" id="dropoff" name="dropoff">
                        <x-slot:label>Specific dropoff location</x-slot:label>
                    </x-inputs.autocomplete>
                @endif
                <div class="row mb-3">
                    <label class="form-label" for="message">Leave a message with your request</label>
                    <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                </div>
                <div class="d-flex justify-content-start gap-2">
                    <button class="btn btn-primary" type="submit">Request</button>
                    <button class="btn btn-primary" id="preview-button" type="button" disabled>Preview</button>
                </div>
            </div>
        </form>
        <script>
            var ride = @json($ride);
        </script>
    </main>
</x-layouts.app>
