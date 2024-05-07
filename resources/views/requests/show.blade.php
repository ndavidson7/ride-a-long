<x-layouts.app class="mx-auto max-w-5xl space-y-3" title="View request">

    <x-typography.h1>View {{ $request->user->name }}'s request</x-typography.h1>

    @if ($request->ride->detours_allowed)
        <x-map :ride="$request->ride" :$request />
    @endif

    <div>
        <x-buk-label class="mb-1 font-medium" for="message">Message</x-buk-label>
        <p id="message">{{ $request->message ?? 'None' }}</p>
    </div>

    <x-cards.user :user="$request->user" />

    @if ($request->ride->user_relation === 'driver' && $request->response === null)
        <x-form class="flex items-center gap-2" action="{{ route('requests.update', $request->id) }}" method="PUT">
            <x-button name="response" type="submit" value="1" size="sm" variant="success">Accept</x-button>
            <x-button name="response" type="submit" value="0" size="sm" variant="danger">Deny</x-button>
        </x-form>
    @elseif ($request->response !== null)
        <div>
            <x-buk-label class="mb-1 font-medium" for="response">Response</x-buk-label>
            <p id="response">{{ $request->response ? 'Accepted' : 'Denied' }}</p>
            @if ($request->user_id === auth()->user()->id)
                <x-button size="sm" as="form" variant="danger"
                    action="{{ route('requests.destroy', $request->id) }}" method="DELETE">Mark as Read</x-button>
            @endif
        </div>
    @endif

</x-layouts.app>
