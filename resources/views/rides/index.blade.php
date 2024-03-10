<x-layouts.app class="mx-auto max-w-5xl space-y-3" title="Ride listings" :$entries>

    <div class="flex gap-2">
        <x-modal.button class="rounded-full p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-800"
            modal-id="ride-filter">
            <x-fas-sliders class="size-8" />
        </x-modal.button>

        <x-modal class="sm:max-w-lg" id="ride-filter" title="Filter">
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
        <ol class="grid auto-rows-fr grid-cols-1 gap-4">
            @foreach ($rides as $ride)
                <li>
                    <x-modal.trigger modal-id="ride-info" x-data="{ rideId: {{ $ride->id }} }"
                        @click="$dispatch('modal:update', { id: modalId, args: { rideId: rideId } });">
                        <x-cards.ride.preview :$ride />
                    </x-modal.trigger>
                </li>
            @endforeach
        </ol>

        <x-modal class="max-w-screen-2xl" id="ride-info" title="Ride info" x-data="{
            ride: null,
            loading: false,
        
            async fetchData(rideId) {
                if (rideId === undefined) throw new TypeError(`Missing argument 'rideId'`);
        
                return fetch(route('rides.show', rideId), {
                        headers: { Accept: 'application/json' },
                    })
                    .then(response => response.json())
                    .then(data => {
                        return data;
                    });
            },
        
            async update(args) {
                if (args.rideId === undefined) throw new TypeError(`Missing arguments property 'rideId'`);
        
                if (args.rideId === this.ride?.id) return;
        
                this.loading = true;
                this.ride = await this.fetchData(args.rideId);
            },
        
            constructDirectionsUrl(addresses) {
                const apple = navigator.userAgent.includes('Mac OS');
        
                if (Array.isArray(addresses)) {
                    return apple ?
                        `maps://https://maps.apple.com/?dirflg=d&saddr=${
                                                                      addresses[0].latitude
                                                                  }%2C${addresses[0].longitude}&daddr=${addresses
                                                                      .flatMap((address, index) =>
                                                                          index > 0
                                                                              ? `${address.latitude}%2C${address.longitude}`
                                                                              : [],
                                                                      )
                                                                      .join('&daddr=')}` :
                        `https://www.google.com/maps/dir/?api=1&travelmode=driving&origin=${
                                                                      addresses[0].latitude
                                                                  }%2C${addresses[0].longitude}&destination=${
                                                                      addresses[addresses.length - 1].latitude
                                                                  }%2C${addresses[addresses.length - 1].longitude}&waypoints=${
                                                                      addresses.length > 2
                                                                          ? addresses
                                                                                .flatMap((address, index) =>
                                                                                    index > 0 && index < addresses.length - 1
                                                                                        ? `${address.latitude}%2C${address.longitude}`
                                                                                        : [],
                                                                                )
                                                                                .join('%7C')
                                                                          : ''
                                                                  }`;
                } else if (typeof addresses === 'object') {
                    return apple ?
                        `maps://https://maps.apple.com/?q=${addresses.latitude}%2C${addresses.longitude}` :
                        `https://www.google.com/maps/search/?api=1&query=${addresses.latitude}%2C${addresses.longitude}`;
                } else {
                    throw new TypeError(
                        'MapComponent.constructDirectionsUrl() requires an object or array of objects.',
                    );
                }
            }
        }"
            x-effect="console.log('Ride:',ride)">
            <x-slot:body>
                <div class="relative space-y-4 lg:space-y-0" x-show="loading">
                    <div class="aspect-video animate-pulse rounded-lg bg-gray-300"></div>
                    {{-- <div
                        class="left-2.5 top-2.5 divide-y divide-[#ddd] rounded-lg bg-white shadow-[0_0_0_2px_rgba(0,0,0,.1)] lg:absolute">
                        <div class="flex flex-wrap items-center gap-3 p-3">
                            <div class="space-x-1">
                                <x-fas-route class="size-5 inline-block text-neutral-600" />
                                <span class="h-6 w-10 animate-pulse bg-neutral-600 align-middle"></span>
                            </div>
                            <div class="space-x-1">
                                <x-fas-clock class="size-5 inline-block text-neutral-600" />
                                <span class="h-6 w-10 animate-pulse bg-neutral-600"></span>
                            </div>
                        </div>
                        <div class="animate-pulse">
                            <div class="h-6 w-32 rounded bg-gray-300 p-3"></div>
                            <div class="h-6 w-40 rounded bg-gray-300 p-3"></div>
                            <div class="h-6 w-28 rounded bg-gray-300 p-3"></div>
                        </div>
                    </div> --}}
                </div>

                <x-map x-show="!loading" x-init="$watch('ride', async value => {
                    await update({
                        origin: [+value?.origin?.longitude, +value?.origin?.latitude],
                        waypoints: value?.waypoints?.map(waypoint => [+waypoint.address.longitude, +waypoint.address.latitude]),
                        destination: [+value?.destination?.longitude, +value?.destination?.latitude]
                    });
                
                    loading = false;
                })" />
            </x-slot:body>

            <x-slot:footer>
                <x-buttons.anchor target="_blank" ::href="ride ? constructDirectionsUrl([ride.origin, ...ride.waypoints.map(waypoint => waypoint.address), ride
                    .destination
                ]) : ''" size="sm">View directions</x-buttons.anchor>
                <x-buttons.anchor ::href="ride ? route('requests.create', ride.id) : ''" size="sm">Request</x-buttons.anchor>
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
