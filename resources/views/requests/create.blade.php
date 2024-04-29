<x-layouts.app class="mx-auto max-w-5xl space-y-3" title="Request to join ride">
    <x-typography.h1>Request to join {{ $ride->driver->name }}'s ride</x-typography.h1>

    @if ($ride->detours_allowed)
        <x-map :$ride />
    @endif

    <x-form class="space-y-3" action="{{ route('requests.store', $ride->id) }}" x-data="{
        pickup: {},
        dropoff: {},
    
        updateMap() {
            if (!this.pickup.formattedAddress || !this.dropoff.formattedAddress) return;
    
            {{-- $dispatch('map:update', [{
                    address: this.pickup.formattedAddress,
                    coordinates: [+this.origin.longitude, +this.origin.latitude],
                },
                {
                    address: this.destination.formattedAddress,
                    coordinates: [+this.destination.longitude, +this.destination.latitude],
                },
            ]); --}}
        }
    }"
        x-init="$watch('pickup', value => updateMap());
        $watch('dropoff', value => updateMap());">
        {{-- <p class="mb-1">All fields are optional</p> --}}

        @if ($ride->detours_allowed)
            <div class="grid gap-3 md:grid-cols-2">
                <div>
                    <x-buk-label class="mb-1 font-medium" for="pickup">Specific pickup location</x-buk-label>
                    <x-inputs.address name="pickup" x-model="pickup" />
                </div>

                <div>
                    <x-buk-label class="mb-1 font-medium" for="dropoff">Specific dropoff location</x-buk-label>
                    <x-inputs.address name="dropoff" x-model="dropoff" />
                </div>
            </div>
        @endif

        <div x-data="{ message: '' }">
            <x-buk-label class="mb-1 font-medium" for="message">Leave a message with your request</x-buk-label>
            <x-inputs.textarea name="message" aria-describedby="message-limit" rows="4" maxlength="255"
                x-model="message"></x-inputs.textarea>
            <p class="text-xs/none text-gray-600" id="message-limit" x-text="`${message.length}/255 characters`"></p>
        </div>

        <x-button size="sm">Request</x-button>
    </x-form>

</x-layouts.app>
