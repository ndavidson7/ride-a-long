@props(['ride'])

<div>
    <x-typography.h2>Pending Requests</x-typography.h2>

    @if ($ride->requests->isNotEmpty())
        <ol>
            {{-- $ride->requests will only return pending requests due to the way soft deletes work --}}
            @foreach ($ride->requests as $request)
                <li class="group flex flex-wrap items-center gap-3 p-3 hover:bg-gray-100">
                    <x-anchors.user :user="$request->user" size="md" />

                    <a class="flex flex-grow flex-wrap items-center gap-3" href="{{ route('requests.show', $request) }}">
                        @if ($request->message)
                            <p>{{ $request->message }}</p>
                        @endif

                        @if ($ride->detours_allowed)
                            <p>Pickup: {{ $request->pickup?->formatted_address ?: 'None' }}</p>
                            <p>Dropoff: {{ $request->dropoff?->formatted_address ?: 'None' }}</p>
                        @endif
                    </a>
                    <x-form class="invisible flex items-center gap-1 group-hover:visible"
                        action="{{ route('requests.update', $request) }}" method="PUT">
                        <x-button class="rounded-full" name="response" value="1" title="Accept request" unstyled>
                            <x-fas-circle-check class="size-6 rounded-full text-green-500" />
                        </x-button>
                        <x-button class="rounded-full" name="response" value="0" title="Deny request" unstyled>
                            <x-fas-circle-xmark class="size-6 rounded-full text-red-500" />
                        </x-button>
                    </x-form>
                </li>
            @endforeach
        </ol>
    @else
        <p>No pending requests.</p>
    @endif
</div>
