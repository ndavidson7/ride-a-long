<x-layouts.app class="mx-auto max-w-5xl space-y-3" title="Request to join ride">
    <x-typography.h1>Request to join {{ $ride->driver->name }}'s ride</x-typography.h1>

    @if ($ride->detours_allowed)
        <x-map :$ride :request="(object) ['pickup' => null, 'dropoff' => null, 'user' => auth()->user()]" />
    @endif

    <x-form action="{{ route('requests.store', $ride->id) }}" x-data="{
        pickup: {},
        dropoff: {},
    }" x-init="$watch('pickup', value => $dispatch('map:set-pickup', value));
    $watch('dropoff', value => $dispatch('map:set-dropoff', value));">

        @if ($errors->any())
            <ul class="mb-1 border border-red-500">
                @foreach ($errors->all() as $error)
                    <li class="text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <p class="mb-1 text-sm text-gray-500">All fields are optional</p>

        <div class="space-y-3">
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
                <p class="text-xs/none text-gray-600" id="message-limit" x-text="`${message.length}/255 characters`">
                </p>
            </div>
            <x-button size="sm">Request</x-button>
        </div>
    </x-form>

</x-layouts.app>
