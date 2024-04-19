@props(['ride'])

<div>
    <x-typography.h2>Passengers</x-typography.h2>

    @if ($ride->passengers->isNotEmpty())
        <ul class="">
            @foreach ($ride->passengers as $passenger)
                <li>
                    <a class="me-auto flex items-center" href="{{ route('users.show', $passenger) }}">
                        <x-pfp class="size-12" :user="$passenger" />
                        <span>{{ $passenger->name }}</span>
                    </a>
                    @if ($ride->user_relation === 'driver')
                        <x-button class="grid place-items-center" as="form"
                            action="{{ route('rides.users.destroy', [$ride, $passenger]) }}" method="DELETE"
                            onclick="return confirm('Are you sure you want to remove this passenger?');">
                            <x-fas-circle-xmark class="size-4 text-red-500" />
                        </x-button>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p>No passengers yet.</p>
    @endif
</div>
