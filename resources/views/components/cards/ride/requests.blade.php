@props(['ride'])

<div>
    <x-typography.h2>Pending Requests</x-typography.h2>

    @if ($ride->requests->isNotEmpty())
        <ol class="">
            @foreach ($ride->requests as $request)
                @if ($request->response === null)
                    <li>
                        <a class="me-auto space-y-1" href="{{ route('requests.show', $request) }}">
                            <div class="flex items-center">
                                <x-pfp class="size-12" :user="$request->user" />
                                <span>{{ $request->user->name }}</span>
                            </div>
                            @if ($request->message)
                                <p>{{ $request->message }}</p>
                            @endif

                            @if ($ride->detours_allowed)
                                <p>Pickup: {{ $request->pickup?->formatted_address ?: 'None' }}</p>
                                <p>Dropoff: {{ $request->dropoff?->formatted_address ?: 'None' }}</p>
                            @endif
                        </a>
                        <x-form class="flex items-center" action="{{ route('requests.update', $request) }}" method="PUT">
                            <x-button name="response" value="1" title="Accept request">
                                <x-fas-circle-check class="size-4 text-green-500" />
                            </x-button>
                            <x-button name="response" value="0" title="Deny request">
                                <x-fas-circle-xmark class="size-4 text-red-500" />
                            </x-button>
                        </x-form>
                    </li>
                @endif
            @endforeach
        </ol>
    @else
        <p>No pending requests.</p>
    @endif
</div>
