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
                    <x-inputs.autocomplete class="row mb-3" name="pickup" id="pickup">
                        <x-slot:label>Specific pickup location</x-slot:label>
                    </x-inputs.autocomplete>
                @endif
                @if ($ride->detours_allowed)
                    <x-inputs.autocomplete class="row mb-3" name="dropoff" id="dropoff">
                        <x-slot:label>Specific dropoff location</x-slot:label>
                    </x-inputs.autocomplete>
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
