@php
    $driver = $ride->driver;
    $car = $driver->car;
@endphp

<div {{ $attributes->merge(['class' => 'card']) }}>
    <h3 class="card-header">Driver</h3>
    <div class="card-body">
        <div class="d-flex align-items-center gap-3">
            @if ($pfpUrl = $driver->pfp_url)
                <a href="{{ route('profile.show', $driver) }}" style="height:6em; width:6em;"><img
                        src="{{ $pfpUrl }}" alt="{{ $driver->name }}'s' profile picture"
                        class="img-fluid rounded-circle shadow-lg"></a>
            @endif
            <div class="d-flex flex-column">
                <h5 class="card-title">{{ $driver->name }}</h5>
                <a href="mailto:{{ $driver->email }}" target="_blank"
                    class="card-subtitle text-body-secondary">{{ $driver->email }}</a>
            </div>
        </div>
        @if (in_array($ride->user_relation, ['driver', 'passenger']))
            <div class="d-flex gap-5 mt-3">
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
    </div>
</div>
