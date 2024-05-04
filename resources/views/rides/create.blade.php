<x-layouts.app class="mx-auto max-w-5xl" title="Create ride">
    <x-typography.h1>Create a ride</x-typography.h1>

    <x-map class="mb-3" />

    <x-form class="space-y-3" action="{{ route('rides.store') }}" x-data="{
        origin: {},
        destination: {},
    
        updateMap() {
            if (!this.origin.formattedAddress || !this.destination.formattedAddress) return;
    
            $dispatch('map:update', [{
                    address: this.origin.formattedAddress,
                    coordinates: [this.origin.longitude, this.origin.latitude],
                },
                {
                    address: this.destination.formattedAddress,
                    coordinates: [this.destination.longitude, this.destination.latitude],
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
                <x-inputs.address name="origin" x-model="origin" required />
            </div>

            <div>
                <x-buk-label class="mb-1 font-medium" for="destination">Destination</x-buk-label>
                <x-inputs.address name="destination" x-model="destination" required />
            </div>
        </div>

        <div class="grid gap-3 md:grid-cols-2">
            <div>
                <x-buk-label class="mb-1 font-medium" for="start-time">Date and Time</x-buk-label>
                <x-inputs.datepicker name="start-time" :options="['enableTime' => true]" required />
            </div>

            <div>
                <x-buk-label class="mb-1 font-medium" for="seats">Seats</x-buk-label>
                <x-inputs.input name="seats" type="number" placeholder="Number of seats available" min="1"
                    required />
            </div>
        </div>

        <div class="flex items-center gap-1">
            <x-inputs.checkbox name="detours" value="1" />
            <x-buk-label class="font-medium" for="detours">Detours allowed</x-buk-label>
            <x-tooltip class="size-4" text="If detours are allowed, you can request a pickup and/or dropoff address."
                position="right">
                <x-fas-circle-info /></x-tooltip>
        </div>

        <div>
            <x-buk-label class="mb-1 font-medium" for="description">Description/Additional info</x-buk-label>
            <x-inputs.textarea name="description" rows=3 maxlength="255"></x-inputs.textarea>
        </div>

        <x-button>Post</x-button>
    </x-form>
</x-layouts.app>
