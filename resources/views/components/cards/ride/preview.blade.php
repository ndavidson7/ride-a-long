<div class="flex cursor-pointer flex-col gap-1 rounded border p-3 shadow-lg hover:bg-blue-100 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500"
    role="button" tabindex="0">
    <h2 class="text-lg font-semibold">{{ $ride->origin->city }}, {{ $ride->origin->state }}
        &#8594;
        {{ $ride->destination->city }}, {{ $ride->destination->state }}
    </h2>
    <div class="flex flex-wrap justify-between gap-2">
        <span class="">{{ $ride->start_time->format('n/j \@ g:i a') }}
        </span>
        <span class=""> {{ $ride->seats_open }} out of {{ $ride->seats_total }}
            seats
            left!
        </span>
        {{-- <h6 class="card-subtitle mb-2">
            Detours Allowed: {{ $ride->detours_allowed ? 'Yes' : 'No' }}
        </h6> --}}
    </div>
    <p class="text-pretty text-sm text-gray-700">{{ $ride->description }}</p>
    <div class="flex flex-wrap items-center gap-1">
        @if ($ride->detours_allowed)
            <span class="rounded-full bg-blue-300 px-2.5 py-1 text-xs font-semibold">Detours</span>
        @endif
    </div>
    <div class="mt-auto">
        @if ($pfp = $ride->driver->fetchFirstMedia())
            <img class="size-8 inline rounded-full shadow-lg" src="{{ $pfp['file_url'] }}" alt="Profile picture">
        @endif
        <span class="">{{ $ride->driver->name }}
        </span>
    </div>
</div>
{{-- <x-buttons.button class="[&>svg]:size-5" data-ride="{{ $ride->id }}"
    data-user-relation="{{ $ride->user_relation }}" data-related-model-id="{{ $ride->related_model_id }}"
    type="button" size="sm">
    @switch($ride->user_relation)
        @case('driver')
            <x-fas-car-side /> Driving
        @break

        @case('requester')
            <x-fas-hourglass-half /> Requested
        @break

        @case('passenger')
            <x-fas-car-side /> Riding
        @break

        @default
            @if ($ride->seats_open > 0)
                <i class="bi bi-info-circle-fill"></i> More info
            @else
                <i class="bi bi-x-circle-fill"></i> Full
            @endif
    @endswitch
</x-buttons.button> --}}
