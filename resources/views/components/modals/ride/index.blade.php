<div x-data="{
    rideData: null,
    userRelation: null,
    relatedModelId: null,
    loading: false,

    {{--
        TODO: Consider storing relevant ride data within JS or HTML on rides index page
        instead of fetching it each time the modal is opened. We already query all the
        data on the index page, so we could store it in a data attribute or JS object.
    --}}
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

        if (rideId === this.rideData?.id) return;

        this.loading = true;
        this.userRelation = userRelation;
        this.relatedModelId = relatedModelId;
        this.rideData = await this.fetchData(rideId);
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
    <x-modal class="max-w-screen-2xl" id="ride-info" title="Ride info">
        <x-slot:body>
            <x-map x-init="$watch('rideData', async value => ride = value)" />
        </x-slot:body>

        <x-slot:footer>
            <x-button as="anchor" target="_blank" ::href="rideData ? constructDirectionsUrl([rideData.origin, ...rideData.waypoints.map(waypoint => waypoint.address),
                rideData.destination
            ]) : ''" ::class="loading && 'pointer-events-none'" size="sm">View
                directions</x-button>
            <x-button as="anchor" ::href="rideData ? route('rides.show', rideData.id) : ''" ::class="loading && 'pointer-events-none'" size="sm">More info</x-button>
            <template x-if="userRelation === 'driver'">
                <x-button as="anchor" ::href="rideData ? route('rides.edit', rideData.id) : ''" size="sm">Edit ride</x-button>
            </template>
            <template x-if="userRelation === 'requester'">
                <x-button as="form" method="delete" ::action="relatedModelId ? route('requests.destroy', relatedModelId) : ''" size="sm">Cancel request</x-button>
            </template>
            <template x-if="userRelation === 'passenger'">
                <x-button as="form" method="delete" ::action="rideData && relatedModelId ? route('rides.users.destroy', [rideData.id, relatedModelId]) : ''" size="sm">Leave ride</x-button>
            </template>
            <template x-if="userRelation === 'none'">
                <x-button as="anchor" ::href="rideData ? route('requests.create', rideData.id) : ''" size="sm">Request to join</x-button>
            </template>
        </x-slot:footer>
    </x-modal>
</div>
