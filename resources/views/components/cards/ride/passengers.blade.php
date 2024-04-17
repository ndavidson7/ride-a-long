@props(['ride'])

<div {{ $attributes->merge(['class' => 'card']) }}>
    <h3 class="card-header">Passengers</h3>
    <div class="card-body">
        <div class="list-group list-group-flush">
            @foreach ($ride->passengers as $passenger)
                <form class="list-group-item list-group-item-action d-flex align-items-center"
                    action="{{ route('rides.users.destroy', [$ride, $passenger]) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a class="d-flex align-items-center w-100 text-decoration-none text-reset me-auto"
                        href="{{ route('users.show', $passenger) }}">
                        @if ($pfp = $passenger->fetchFirstMedia())
                            <img class="rounded-circle me-2 shadow-lg" src="{{ $pfp['file_url'] }}"
                                alt="{{ $passenger->name }}'s profile picture" style="height:3em;">
                        @endif
                        <span class="fs-5">{{ $passenger->name }}</span>
                    </a>
                    @if ($ride->user_relation === 'driver')
                        <button class="btn" type="submit" title="Remove passenger"
                            onclick="return confirm('Are you sure you want to remove this passenger?');"><i
                                class="bi bi-x-lg text-danger fs-4"></i></button>
                    @endif
                </form>
            @endforeach
        </div>
    </div>
</div>
