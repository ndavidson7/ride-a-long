<x-layouts.main title="Create ride" :$entries>
    <main class="py-4">
        <div class="container col-11 col-sm-9 col-md-7 col-lg-6 col-xl-5 col-xxl-4">
            <h1 class="text-center mb-3">Preview</h1>
            <div class="row mb-3">
                <x-map class="d-none" />
            </div>
        </div>

        <form id="ride-create" class="container col-sm-10 col-md-8 col-lg-6" action="{{ route('rides.store') }}"
            method="post" disabled>
            @csrf
            <h1 class="text-center mb-3">Ride Details</h1>

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

            <x-inputs.autocomplete class="mb-3" name="origin" id="origin" required>
                <x-slot:label>Origin</x-slot:label>
            </x-inputs.autocomplete>

            <x-inputs.autocomplete class="mb-3" name="destination" id="destination" required>
                <x-slot:label>Destination</x-slot:label>
            </x-inputs.autocomplete>

            <div class="form-check mb-3">
                <label class="form-check-label" for="detours-checkbox">Allow Detours <a href="#"
                        data-bs-toggle="tooltip" data-bs-placement="right"
                        data-bs-title="If detours are allowed, you can request pickup and/or dropoff locations that are different than the ride's origin and destination"><i
                            class="bi bi-question-circle"></i></a></label>
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
                    <label class="btn btn-primary" for="pricing1">Per Seat</label>
                    <input type="radio" class="btn-check" name="pricing" id="pricing2" value="mile"
                        autocomplete="off" required />
                    <label class="btn btn-primary" for="pricing2">Per Mile</label>
                </div>
            </div> --}}

            <div class="mb-3">
                <label class="form-label" for="description">Description/Additional info</label>
                <textarea class="form-control" id="description" name="description" rows=3 maxlength="255"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" disabled>Post</button>
            <button type="button" class="btn btn-primary" id="preview-button" disabled>Preview</button>
        </form>
    </main>
</x-layouts.main>
