<div {{ $attributes->merge(['class' => 'card']) }}>
    <h3 class="card-header">Passengers</h3>
    <div class="card-body">
        <div class="list-group list-group-flush">
            @foreach ($ride->passengers as $passenger)
                <form action="{{ route('rides.users.destroy', [$ride, $passenger]) }}" method="POST"
                    class="list-group-item list-group-item-action d-flex align-items-center">
                    @method('DELETE')
                    @csrf
                    <a href="{{ route('users.show', $passenger) }}"
                        class="d-flex align-items-center me-auto w-100 text-decoration-none text-reset">
                        @if ($pfp = $passenger->fetchFirstMedia())
                            <img src="{{ $pfp['file_url'] }}" alt="{{ $passenger->name }}'s profile picture"
                                class="rounded-circle shadow-lg me-2" style="height:3em;">
                        @endif
                        <span class="fs-5">{{ $passenger->name }}</span>
                    </a>
                    @if ($ride->user_relation === 'driver')
                        <button type="submit" class="btn" title="Remove passenger"
                            onclick="return confirm('Are you sure you want to remove this passenger?');"><i
                                class="bi bi-x-lg text-danger fs-4"></i></button>
                    @endif
                </form>
            @endforeach
        </div>
    </div>
</div>
