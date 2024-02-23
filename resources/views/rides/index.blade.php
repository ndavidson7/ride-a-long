<x-layouts.app class="space-y-3" title="Ride listings" :$entries>

    <div class="flex gap-2">
        <x-ride-filter class="inline-block" />

        <ul class="flex flex-wrap items-center gap-2">
            @foreach ($filters as $filter => $value)
                <li>
                    <button>
                        <x-pill class="bg-gray-300 px-3 py-1.5 text-sm">{{ $filter }}: {{ $value }}</x-pill>
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    @if ($rides->count())
        <ol class="grid auto-rows-fr grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
            <li class="rounded border bg-white shadow-lg">
                <a class="size-full grid place-items-center text-blue-500 hover:text-blue-700"
                    href="{{ route('rides.create') }}">
                    <x-fas-circle-plus class="size-16" /></a>
            </li>

            @foreach ($rides as $ride)
                <x-cards.ride.preview :$ride />
            @endforeach
        </ol>
        <div>
            {{ $rides->links() }}
        </div>
        {{-- <x-modals.ride /> --}}
    @else
        @php
            $heading = request('my-rides') ? 'You have no rides' : 'No rides available';
            if (request('origin-city')) {
                $heading .= ' from ' . request('origin-city');
            }
            if (request('destination-city')) {
                $heading .= ' to ' . request('destination-city');
            }
            if (request('start-date')) {
                $heading .= ' on ' . Carbon\Carbon::parse(request('start-date'))->format('F j, Y');
            }
            if (request('detours')) {
                $heading .= ' with detours allowed';
            }
            $heading .= '.';
        @endphp

        <div class="mt-5 text-center">
            <h1 class="fs-3">{{ $heading }}</h1>
            <p class="fs-4">Be the first to <a href="{{ route('rides.create') }}">post</a> one, or <a
                    href="{{ route('new-ride-alerts.create') }}">set an alert</a> to
                be notified when a ride becomes available!</p>
        </div>
    @endif

</x-layouts.app>
