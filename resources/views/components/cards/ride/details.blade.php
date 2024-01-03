<div {{ $attributes->merge(['class' => 'card']) }}>
    <h3 class="card-header">Ride Details</h3>
    <div class="card-body">
        <x-map />
        {{-- <div class="d-flex flex-column"> --}}
        <h5 class="card-title">{{ $ride->start_time->setTimezone('America/New_York')->format('l, F j \a\t g:i A') }}</h5>
        <p class="card-text">{{ $ride->description }}</p>
        @switch($ride->user_relation)
            @case('driver')
                <a href="{{ route('rides.edit', $ride) }}" class="btn btn-primary btn-bold">Edit Ride</a>
            @break

            @case('requester')
                <form action="{{ route('requests.destroy',$ride->requests()->where('user_id', auth()->user()->id)->first()) }}"
                    method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-bold">Cancel Request</button>
                </form>
            @break

            @case('passenger')
                <form action="{{ route('ride-user.destroy', [$ride, auth()->user()]) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-bold">Leave Ride</button>
                </form>
            @break

            @default
                @if ($ride->seats_open > 0)
                    <a href="{{ route('requests.create', $ride) }}" class="btn btn-primary btn-bold">Request to Join</a>
                @endif
        @endswitch
        {{-- </div> --}}
    </div>
</div>
