@php
    $driver = $ride->driver;
    $car = $driver->car;
@endphp

<x-layouts.app class="mx-auto max-w-7xl" title="{{ $driver->name }}'s ride">

    <x-card class="bg-white">
        <x-typography.h2>Ride Details</x-typography.h2>

        <x-map />

        <h5 class="card-title">{{ $ride->start_time->setTimezone('America/New_York')->format('l, F j \a\t g:i A') }}</h5>
        <p class="card-text">{{ $ride->description }}</p>

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
                @endif
        @endswitch
    </x-card>

    <x-card class="bg-white">
        <x-typography.h2>Driver</x-typography.h2>

        <x-cards.user :user="$driver" />
        @if (in_array($ride->user_relation, ['driver', 'passenger']))
            <div class="d-flex mt-3 gap-5">
                <div class="d-flex flex-column">
                    <h5 class="card-title">Car</h5>
                    <h6 class="card-subtitle text-body-secondary">{{ $car->color }} {{ $car->make }}</h6>
                </div>
                <div class="d-flex flex-column">
                    <h5 class="card-title">License Plate</h5>
                    <h6 class="card-subtitle text-body-secondary">{{ $car->license_plate }}</h6>
                </div>
            </div>
        @endif
    </x-card>

    @if (in_array($ride->user_relation, ['driver', 'passenger']))
        <x-cards.ride.conversation class="mb-3" :$ride :$messageWrappers />

        <x-cards.ride.passengers class="mb-3" :$ride />
        @if ($ride->user_relation === 'driver')
            <x-cards.ride.requests :$ride />
        @endif
    @endif
</x-layouts.app>
