@props(['ride'])

<div>
    <x-typography.h2>Passengers</x-typography.h2>

    @if ($ride->passengers->isNotEmpty())
        <ul class="">
            @foreach ($ride->passengers as $passenger)
                <li class="flex items-center justify-between gap-2">
                    <x-anchors.user :user="$passenger" size="md" />
                    @if ($ride->user_relation === 'driver')
                        <x-button class="grid place-items-center rounded-full" as="form"
                            action="{{ route('rides.users.destroy', [$ride, $passenger]) }}" method="DELETE"
                            onclick="return confirm('Are you sure you want to remove this passenger?');" unstyled>
                            <x-fas-circle-xmark class="size-6 rounded-full text-red-500" />
                        </x-button>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p>No passengers yet.</p>
    @endif
</div>
