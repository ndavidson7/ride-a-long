@php
    $driver = $ride->driver;
    $car = $driver->car;
@endphp

<x-layouts.app class="mx-auto max-w-7xl space-y-3" title="{{ $driver->name }}'s ride">

    <x-card class="bg-white">
        <x-typography.h2>Ride Details</x-typography.h2>
        <div class="space-y-3">
            <x-map :$ride />
            <div x-data="{ date: dayjs({{ Js::from($ride->start_time) }}) }">
                <time datetime="{{ $ride->start_time->toIso8601String() }}" x-text="date.format('LLLL')"></time>
            </div>
            <p>{{ $ride->description }}</p>
            <div class="flex flex-wrap items-center gap-1">
                <x-pill class="gap-1 bg-blue-300 px-2.5 py-1 text-xs font-semibold">
                    {{ $ride->seats_total - $ride->seats_open }} passengers
                </x-pill>
                @if ($ride->seats_open > 0)
                    <x-pill @class([
                        'gap-1',
                        'bg-blue-300' => $ride->seats_open > 2,
                        'bg-yellow-300' => $ride->seats_open <= 2,
                        'px-2.5',
                        'py-1',
                        'text-xs',
                        'font-semibold',
                    ])>
                        {{ $ride->seats_open }} {{ $ride->seats_open == 1 ? 'seat' : 'seats' }} left
                    </x-pill>
                @else
                    <x-pill class="gap-1 bg-red-300 px-2.5 py-1 text-xs font-semibold">
                        Full
                    </x-pill>
                @endif
                @if ($ride->detours_allowed)
                    <x-pill class="gap-1 bg-blue-300 px-2.5 py-1 text-xs font-semibold">
                        <x-fas-arrows-turn-to-dots class="size-3" /> Detours
                    </x-pill>
                @endif
            </div>
            @switch($ride->user_relation)
                @case('driver')
                    <x-button href="{{ route('rides.edit', $ride) }}" as="anchor" size="sm">Edit ride</x-button>
                @break

                @case('requester')
                    <x-button as="form" method="delete" action="{{ route('requests.destroy', $ride->relatedModelId) }}"
                        size="sm">Cancel request</x-button>
                @break

                @case('passenger')
                    <x-button as="form" method="delete"
                        action="{{ route('rides.users.destroy', [$ride->id, $ride->relatedModelId]) }}" size="sm">Leave
                        ride</x-button>
                @break

                @default
                    @if ($ride->seats_open > 0)
                        <x-button href="{{ route('requests.create', $ride) }}" as="anchor" size="sm">Request to
                            join</x-button>
                        {{-- @else
                            <x-button class="bg-red-500" size="sm" disabled>Full</x-button> --}}
                    @endif
            @endswitch
        </div>
    </x-card>
    <x-card class="bg-white">
        <x-typography.h2>Driver</x-typography.h2>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <x-cards.user :user="$driver" />
            @if (in_array($ride->user_relation, ['driver', 'passenger']))
                {{-- TODO: This looks terrible --}}
                <div class="flex items-center gap-1">
                    <x-fas-car class="size-16 text-gray-500" />
                    <div class="font-medium">
                        <div>{{ $car->color }} {{ $car->make }}</div>
                        <div>{{ $car->license_plate }}</div>
                    </div>
                </div>
            @endif
        </div>
    </x-card>

    @if (in_array($ride->user_relation, ['driver', 'passenger']))
        <x-card class="bg-white">
            <x-cards.ride.conversation :$ride :$participants :$messageWrappers />
            {{-- :$lastPage --}}
        </x-card>
        <x-card class="bg-white">
            <x-cards.ride.passengers :$ride />
        </x-card>
        @if ($ride->user_relation === 'driver')
            <x-card class="bg-white">
                <x-cards.ride.requests :$ride />
            </x-card>
        @endif
    @endif
</x-layouts.app>
