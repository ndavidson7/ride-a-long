<x-modals.modal title="Filter" {{ $attributes }}>

    <x-slot:button class="rounded-full p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-800">
        <x-fas-sliders class="h-8 w-8" />
    </x-slot:button>

    <x-slot:body>
        <form class="grid gap-2" id="filter" method="get">

            <x-inputs.address name="origin" size="sm" placeholder="Origin City" layers="locality" />

            <x-inputs.address name="destination" size="sm" placeholder="Destination City" layers="locality" />

            <x-inputs.input name="start-date" type="date" value="{{ request('start-date') }}" size="sm"
                min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d') }}" />

            <div class="text-nowrap flex items-center gap-1">
                <x-inputs.checkbox name="detours" value="1" :checked="request('detours') ?? false" />
                <x-buk-label for="detours">Detours allowed</x-buk-label>
                <x-tooltip class="h-4 w-4"
                    text="If detours are allowed, you can request a pickup and/or dropoff address." position="right">
                    <x-fas-circle-info /></x-tooltip>
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

        </form>
    </x-slot:body>

    <x-slot:footer>
        <x-buttons.button form="filter" size="sm">Filter</x-buttons.button>
    </x-slot:footer>

</x-modals.modal>
