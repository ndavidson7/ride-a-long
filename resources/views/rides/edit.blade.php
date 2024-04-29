<x-layouts.app class="mx-auto max-w-5xl space-y-3" title="Edit ride">
    <x-typography.h1>Edit ride</x-typography.h1>

    <x-map :$ride />

    <x-form class="space-y-3" id="update-ride" action="{{ route('rides.update', $ride) }}" method="PUT"
        x-data="{
            origin: {},
            destination: {},
        
            updateMap() {
                if (!this.origin.formattedAddress || !this.destination.formattedAddress) return;
        
                $dispatch('map:update', [{
                        address: this.origin.formattedAddress,
                        coordinates: [+this.origin.longitude, +this.origin.latitude],
                    },
                    {
                        address: this.destination.formattedAddress,
                        coordinates: [+this.destination.longitude, +this.destination.latitude],
                    },
                ]);
            }
        }" x-init="$watch('origin', value => updateMap());
        $watch('destination', value => updateMap());">

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        @endif

        <div class="grid gap-3 md:grid-cols-2">
            <div>
                <x-buk-label class="mb-1 font-medium" for="origin">Origin</x-buk-label>
                <x-inputs.address name="origin" x-model="origin" :address="$ride->origin" required />
            </div>

            <div>
                <x-buk-label class="mb-1 font-medium" for="destination">Destination</x-buk-label>
                <x-inputs.address name="destination" x-model="destination" :address="$ride->destination" required />
            </div>
        </div>

        <div class="grid gap-3 md:grid-cols-2">
            <div>
                <x-buk-label class="mb-1 font-medium" for="start-time">Date and Time</x-buk-label>
                <x-inputs.datepicker name="start-time" :options="['enableTime' => true, 'defaultDate' => $ride->start_time]" required />
            </div>

            <div>
                <x-buk-label class="mb-1 font-medium" for="seats">Seats</x-buk-label>
                <x-inputs.input name="seats" type="number" placeholder="Number of seats available" :value="$ride->seats_total"
                    min="1" required />
            </div>
        </div>

        <div class="flex items-center gap-1">
            <x-inputs.checkbox name="detours" value="1" :checked="$ride->detours_allowed" />
            <x-buk-label class="font-medium" for="detours">Detours allowed</x-buk-label>
            <x-tooltip class="size-4" text="If detours are allowed, you can request a pickup and/or dropoff address."
                position="right">
                <x-fas-circle-info /></x-tooltip>
        </div>

        <div>
            <x-buk-label class="mb-1 font-medium" for="description">Description/Additional info</x-buk-label>
            <x-inputs.textarea name="description" rows=3 maxlength="255">{{ $ride->description }}</x-inputs.textarea>
        </div>

    </x-form>
    <x-form id="delete-ride" action="{{ route('rides.destroy', $ride) }}" method="DELETE" />

    <x-button form="update-ride">Save</x-button>
    <x-button form="delete-ride" variant="danger">Delete</x-button>

</x-layouts.app>
