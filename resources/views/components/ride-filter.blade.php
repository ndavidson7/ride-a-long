<x-modals.modal class="p-3" title="Filter">

    <x-slot:button>
        <x-fas-filter class="h-5 w-5" />
    </x-slot:button>

    <x-slot:body>
        <x-form class="grid gap-2" id="filter" method="get">

            <x-inputs.input name="origin-city" value="{{ request('origin-city') }}" size="sm"
                placeholder="Origin City" />

            <x-inputs.input name="destination-city" value="{{ request('destination-city') }}" size="sm"
                placeholder="Destination City" />

            <x-inputs.input name="start-date" type="date" value="{{ request('start-date') }}" size="sm"
                placeholder="Origin City"
                min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d') }}" />

            <div class="text-nowrap flex items-center gap-1">
                <x-inputs.checkbox name="detours" value="1" :checked="request('detours') ?? false" />
                <x-buk-label for="detours">Detours allowed</x-buk-label>
                <x-tooltip class="h-4 w-4"
                    text="If detours are allowed, you can request pickup and/or dropoff locations that are different than the ride's origin and destination"
                    position="right">
                    <x-far-circle-question /></x-tooltip>
            </div>

            {{-- <div class=" form-check">
                                <label class="form-check-label" for="exclude-full-checkbox">Exclude Full Rides</label>
                                <input type="checkbox" class="form-check-input" id="exclude-full-checkbox" name="exclude-full"
                                    value="1" @if (request('exclude-full')) checked @endif />
                            </div> --}}

            <div class="text-nowrap flex items-center gap-1">
                <x-inputs.checkbox name="my-rides" value="1" :checked="request('my-rides') ?? false" />
                <x-buk-label for="my-rides">My rides</x-buk-label>
            </div>

        </x-form>
    </x-slot:body>

    <x-slot:footer>
        <x-buttons.button form="filter" size="sm">Filter</x-buttons.button>
    </x-slot:footer>

</x-modals.modal>
