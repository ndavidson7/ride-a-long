<div {{ $attributes->merge(['class' => 'card']) }}>
    <h3 class="card-header">Pending Requests</h3>
    <div class="card-body">
        <div class="list-group">
            @foreach ($ride->requests as $request)
                @if ($request->response === null)
                    <form action="{{ route('requests.update', $request) }}" method="POST"
                        class="list-group-item list-group-item-action d-flex align-items-center">
                        @method('PUT')
                        @csrf
                        <a href="{{ route('requests.show', $request) }}"
                            class="me-auto w-100 text-decoration-none text-reset">
                            <div class="d-flex align-items-center my-1">
                                @if ($pfp = $request->user->fetchFirstMedia())
                                    <img src="{{ $pfp['file_url'] }}" alt="{{ $request->user->name }}'s profile picture"
                                        class="rounded-circle shadow-lg me-2" style="height:3em;">
                                @endif
                                <span class="fs-5">{{ $request->user->name }}</span>
                            </div>
                            @if ($request->message)
                                <p class="mb-1">{{ $request->message }}</p>
                            @endif
                            <p class="mb-1">Pickup: {{ $request->pickup ?: 'None' }}</p>
                            <p class="mb-1">Dropoff: {{ $request->dropoff ?: 'None' }}</p>
                        </a>
                        <button type="submit" class="btn" name="response" value="1" title="Accept request"><i
                                class="bi bi-check-lg text-success fs-3"></i></button>
                        <button type="submit" class="btn" name="response" value="0" title="Deny request"><i
                                class="bi bi-x-lg text-danger fs-4"></i></button>
                    </form>
                @endif
            @endforeach
        </div>
    </div>
</div>
