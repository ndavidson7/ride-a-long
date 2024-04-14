<x-modal class="max-w-screen-2xl" id="ride-info" title="Ride info" x-data="{
    ride: null,
    userRelation: null,
    relatedModelId: null,
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
        const { rideId, userRelation, relatedModelId } = args;

        if (rideId === undefined || userRelation === undefined || relatedModelId === undefined) throw new TypeError(`Missing argument 'rideId', 'userRelation', or 'relatedModelId'`);

        if (rideId === this.ride?.id) return;

        this.loading = true;
        this.userRelation = userRelation;
        this.relatedModelId = relatedModelId;
        this.ride = await this.fetchData(rideId);
    },

    constructDirectionsUrl(addresses) {
        const apple = navigator.userAgent.includes('Mac OS');

        {{-- blade-formatter-disable --}}
                if (Array.isArray(addresses)) {
                    return apple
                        ? `maps://https://maps.apple.com/?dirflg=d&saddr=${
                                addresses[0].latitude
                            }%2C${addresses[0].longitude}&daddr=${addresses
                                .flatMap((address, index) =>
                                    index > 0
                                        ? `${address.latitude}%2C${address.longitude}`
                                        : [],
                                )
                                .join('&daddr=')}`
                        : `https://www.google.com/maps/dir/?api=1&travelmode=driving&origin=${
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
                    throw new TypeError('Argument must be an object or array of objects:', addresses);
                }
                {{-- blade-formatter-enable --}}
    }
}">
    <x-slot:body>
        <div class="relative space-y-4 lg:space-y-0" x-show="loading">
            <div class="aspect-video animate-pulse rounded-lg bg-gray-300"></div>
        </div>

        <x-map x-show="!loading" x-init="$watch('ride', async value => {
            await update([{
                    address: value?.origin?.address,
                    coordinates: [+value?.origin?.longitude, +value?.origin?.latitude],
                },
                ...value?.waypoints?.map(waypoint => {
                    return {
                        address: waypoint.address.address,
                        coordinates: [+waypoint.address.longitude, +waypoint.address.latitude],
                    };
                }),
                {
                    address: value?.destination?.address,
                    coordinates: [+value?.destination?.longitude, +value?.destination?.latitude],
                },
            ]);
        
            loading = false;
        })" />
    </x-slot:body>

    <x-slot:footer>
        <x-button as="anchor" target="_blank" ::href="ride ? constructDirectionsUrl([ride.origin, ...ride.waypoints.map(waypoint => waypoint.address),
            ride.destination
        ]) : ''" ::class="loading && 'pointer-events-none'" size="sm">View
            directions</x-button>
        <x-button as="anchor" ::href="ride ? route('rides.show', ride.id) : ''" ::class="loading && 'pointer-events-none'" size="sm">More info</x-button>
        <template x-if="userRelation == 'driver'">
            <x-button as="anchor" ::href="ride ? route('rides.edit', ride.id) : ''" size="sm">Edit ride</x-button>
        </template>
        <template x-if="userRelation == 'requester'">
            <x-button as="form" method="delete" ::action="relatedModelId ? route('requests.destroy', relatedModelId) : ''" size="sm">Cancel request</x-button>
        </template>
        <template x-if="userRelation == 'passenger'">
            <x-button as="form" method="delete" ::action="ride && relatedModelId ? route('rides.users.destroy', [ride.id, relatedModelId]) : ''" size="sm">Leave ride</x-button>
        </template>
    </x-slot:footer>
</x-modal>
