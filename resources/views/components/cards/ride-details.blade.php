<div {{ $attributes->merge(['class' => 'card']) }}>
    <h3 class="card-header">Ride Details</h3>
    <div class="card-body">
        <x-map />
        @switch($ride->user_relation)
            @case('driver')
                <a href="{{ route('rides.edit', $ride) }}" class="btn btn-uva-ob">Edit Ride</a>
            @break

            @case('requester')
                <form action="{{ route('requests.destroy',$ride->requests()->where('user_id', auth()->user()->id)->first()) }}"
                    method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Cancel Request</button>
                </form>
            @break

            @case('passenger')
                <form action="{{ route('ride-user.destroy', [$ride, auth()->user()]) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Leave Ride</button>
                </form>
            @break

            @default
                @if ($ride->seats_open > 0)
                    <a href="{{ route('requests.create', $ride) }}" class="btn btn-uva-ob">Request to Join</a>
                @endif
        @endswitch
    </div>
</div>
