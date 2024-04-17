@props(['ride'])

<div {{ $attributes->merge(['class' => 'card']) }}>
    <h3 class="card-header">Pending Requests</h3>
    <div class="card-body">
        <div class="list-group">
            @foreach ($ride->requests as $request)
                @if ($request->response === null)
                    <form class="list-group-item list-group-item-action d-flex align-items-center"
                        action="{{ route('requests.update', $request) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <a class="w-100 text-decoration-none text-reset me-auto"
                            href="{{ route('requests.show', $request) }}">
                            <div class="d-flex align-items-center my-1">
                                @if ($pfp = $request->user->fetchFirstMedia())
                                    <img class="rounded-circle me-2 shadow-lg" src="{{ $pfp['file_url'] }}"
                                        alt="{{ $request->user->name }}'s profile picture" style="height:3em;">
                                @endif
                                <span class="fs-5">{{ $request->user->name }}</span>
                            </div>
                            @if ($request->message)
                                <p class="mb-1">{{ $request->message }}</p>
                            @endif
                            <p class="mb-1">Pickup: {{ $request->pickup?->address ?: 'None' }}</p>
                            <p class="mb-1">Dropoff: {{ $request->dropoff?->address ?: 'None' }}</p>
                        </a>
                        <button class="btn" name="response" type="submit" value="1" title="Accept request"><i
                                class="bi bi-check-lg text-success fs-3"></i></button>
                        <button class="btn" name="response" type="submit" value="0" title="Deny request"><i
                                class="bi bi-x-lg text-danger fs-4"></i></button>
                    </form>
                @endif
            @endforeach
        </div>
    </div>
</div>
