<x-layouts.app class="mx-auto max-w-5xl space-y-3" title="Create ride">
    <x-map />

    <x-form class="" action="{{ route('rides.store') }}">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        @endif

        <x-buk-label class="font-medium" for="start-time">Date and Time</x-buk-label>
        {{-- <input class="form-control" id="start-time" name="start-time" type="datetime-local"
            min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d\TH:i') }}" required /> --}}
        <x-inputs.datepicker name="start-time" :options="['enableTime' => true]" required />

        <label class="form-label" for="seats">Seats</label>
        <input class="form-control" id="seats" name="seats" type="number" min="1" required />

        <x-inputs.autocomplete class="mb-3" id="origin" name="origin" required>
            <x-slot:label>Origin</x-slot:label>
        </x-inputs.autocomplete>

        <x-inputs.autocomplete class="mb-3" id="destination" name="destination" required>
            <x-slot:label>Destination</x-slot:label>
        </x-inputs.autocomplete>

        <div class="form-check mb-3">
            <label class="form-check-label" for="detours-checkbox">Allow Detours <a data-bs-toggle="tooltip"
                    data-bs-placement="right"
                    data-bs-title="If detours are allowed, you can request pickup and/or dropoff locations that are different than the ride's origin and destination"
                    href="#"><i class="bi bi-question-circle"></i></a></label>
            <input class="form-check-input" id="detours-checkbox" name="detours" type="checkbox" />
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

        <x-button class="bg-white" size="sm">Post</x-button>
        <x-button class="bg-white" type="button">Preview</x-button>
    </x-form>
</x-layouts.app>
