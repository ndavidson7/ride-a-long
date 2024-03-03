<x-layouts.app class="mx-auto max-w-5xl space-y-3" title="Ride listings" :$entries>

    <div class="flex gap-2">
        <x-modal.button class="rounded-full p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-800"
            modal-id="ride-filter">
            <x-fas-sliders class="size-8" />
        </x-modal.button>

        <x-modal id="ride-filter" title="Filter" size="sm">
            <x-slot:body>
                <x-ride-filter />
            </x-slot:body>

            <x-slot:footer>
                <x-buttons.button form="filter" size="sm">Filter</x-buttons.button>
            </x-slot:footer>
        </x-modal>

        <ul class="flex flex-wrap items-center gap-2">
            @foreach ($filters as $filter => $value)
                <li>
                    <button>
                        <x-pill class="bg-gray-300 px-3 py-1.5 text-sm">{{ $filter }}:
                            {{ $value }}</x-pill>
                    </button>
                </li>
            @endforeach
        </ul>

        <a class="ms-auto rounded-full p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-800"
            href="{{ route('rides.create') }}">
            <x-fas-plus class="size-8" />
        </a>
    </div>

    @if ($rides->count())
        {{-- sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 --}}
        <ol class="grid auto-rows-fr grid-cols-1 gap-4">
            @foreach ($rides as $ride)
                {{-- <li data-ride="{{ $ride->id }}" role="button" tabindex="0"
                    @click="open=true; $dispatch('mapupdate', { rideId: $el.dataset.ride });">
                    <x-cards.ride.preview :$ride />
                </li> --}}
                <li>
                    {{-- data-ride-id="{{ $ride->id }}" x-data @click="$dispatch('modal:update', { id: modalId, args: { rideId: $el.dataset.rideId } })" --}}
                    <x-modal.trigger modal-id="ride-info">
                        <x-cards.ride.preview :$ride />
                    </x-modal.trigger>
                </li>
            @endforeach
        </ol>

        {{-- :x-data="['ride' => null]" --}}
        <x-modal id="ride-info" title="Ride info" size="lg" x-data="{
            ride: null,
        
            async fetchData(rideId) {
                if (rideId === undefined) throw new TypeError(`Missing argument 'rideId'`);
        
                return fetch(route('rides.show', rideId), {
                        headers: { Accept: 'application/json' },
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        return data;
                    });
            },
        
            async update(rideId) {
                this.ride = await this.fetchData(rideId);
            }
        }" x-effect="console.log(ride)">
            <x-slot:body>
                <x-map />

                <ol class="space-y-3 pl-1">
                    <li
                        class="after:size-2.5 relative bg-contain bg-no-repeat pl-4 before:absolute before:bottom-0 before:left-1 before:top-1/2 before:w-0.5 before:bg-gray-700 after:absolute after:left-0 after:top-1/2 after:-translate-y-1/2 after:bg-list-bullet">
                        first</li>
                    <li
                        class="after:size-2.5 relative bg-contain bg-no-repeat pl-4 before:absolute before:-bottom-3 before:-top-3 before:left-1 before:w-0.5 before:bg-gray-700 after:absolute after:left-0 after:top-1/2 after:-translate-y-1/2 after:bg-list-bullet">
                        second</li>
                    <li
                        class="after:size-2.5 relative bg-contain bg-no-repeat pl-4 before:absolute before:bottom-1/2 before:left-1 before:top-0 before:w-0.5 before:bg-gray-700 after:absolute after:left-0 after:top-1/2 after:-translate-y-1/2 after:bg-list-bullet">
                        third</li>
                </ol>
                {{-- <template x-for="">

                </template> --}}
            </x-slot:body>

            <x-slot:footer>
                <x-buttons.button size="sm">Test button</x-buttons.button>
            </x-slot:footer>
        </x-modal>

        {{ $rides->links() }}
    @else
        @php
            $heading = request('my-rides') ? 'You have no rides' : 'No rides available';
            if (request('origin-city')) {
                $heading .= ' from ' . request('origin-city');
            }
            if (request('destination-city')) {
                $heading .= ' to ' . request('destination-city');
            }
            if (request('start-date')) {
                $heading .= ' on ' . Carbon\Carbon::parse(request('start-date'))->format('F j, Y');
            }
            if (request('detours')) {
                $heading .= ' with detours allowed';
            }
            $heading .= '.';
        @endphp

        <div class="mt-5 text-center">
            <h1 class="fs-3">{{ $heading }}</h1>
            <p class="fs-4">Be the first to <a href="{{ route('rides.create') }}">post</a> one, or <a
                    href="{{ route('new-ride-alerts.create') }}">set an alert</a> to
                be notified when a ride becomes available!</p>
        </div>
    @endif

</x-layouts.app>
