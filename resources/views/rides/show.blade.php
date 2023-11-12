<x-layouts.main title="{{ $ride->driver->first_name }} {{ $ride->driver->last_name }}'s ride" :$entries>
    <main class="container py-4">
        <div class="row">
            <div @class(['col', 'col-lg-6' => $ride->user_relation === 'driver'])>
                <h2 class="text-center mb-3">Details</h2>
                <div class="row mb-3">
                    <x-map />
                </div>
                @switch($ride->user_relation)
                    @case('driver')
                        <a href="{{ route('rides.edit', $ride) }}" class="btn btn-uva-ob">Edit</a>
                    @break

                    @case('requester')
                        <form
                            action="{{ route('requests.destroy',$ride->requests()->where('user_id', auth()->user()->id)->first()) }}"
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
            @if ($ride->user_relation === 'driver')
                <div class="col-lg-6">
                    <h2 class="text-center mb-3">Pending Requests</h2>
                    <div class="list-group">
                        @foreach ($ride->requests as $request)
                            @if ($request->response === null)
                                <form action="{{ route('requests.update', $request) }}" method="POST"
                                    class="list-group-item list-group-item-action d-flex align-items-center">
                                    @method('PUT')
                                    @csrf
                                    <a href="{{ route('requests.show', $request) }}"
                                        class="me-auto w-100 text-decoration-none text-reset">
                                        <div class="d-flex w-100 mt-1">
                                            @if ($pfp = $request->user->fetchFirstMedia())
                                                <img src="{{ $pfp['file_url'] }}"
                                                    alt="{{ $request->user->first_name }} {{ $request->user->last_name }}'s profile picture"
                                                    class="rounded-circle shadow-lg ms-2"
                                                    style="display:inline-block; height:3em; width:auto;">
                                            @endif
                                            <h5>{{ $request->user->first_name }} {{ $request->user->last_name }}</h5>
                                        </div>
                                        @if ($request->message)
                                            <p class="mb-1">{{ $request->message }}</p>
                                        @endif
                                        <p class="mb-1">Pickup: {{ $request->pickup ?: 'None' }}</p>
                                        <p class="mb-1">Dropoff: {{ $request->dropoff ?: 'None' }}</p>
                                    </a>
                                    <button type="submit" class="btn" name="response" value="1"><i
                                            class="bi bi-check-lg text-success fs-3"></i></button>
                                    <button type="submit" class="btn" name="response" value="0"><i
                                            class="bi bi-x-lg text-danger fs-4"></i></button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>
    <script>
        var ride = @json($ride);
    </script>
</x-layouts.main>
