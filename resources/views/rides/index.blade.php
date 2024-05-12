<x-layouts.app class="mx-auto max-w-5xl space-y-3" title="Ride listings">

    {{-- Hotbar (ride filters and create ride buttons) --}}
    <div class="flex gap-2">
        <x-modal.button class="rounded-full p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-800" target="ride-filter">
            <x-fas-sliders class="size-8" />
        </x-modal.button>

        <x-modal class="sm:max-w-lg" id="ride-filter" title="Filter">
            <x-slot:body>
                <x-ride-filter />
            </x-slot:body>

            <x-slot:footer>
                <x-button form="filter" size="sm">Filter</x-button>
            </x-slot:footer>
        </x-modal>

        <ul class="flex flex-wrap items-center gap-2">
            @foreach ($filters as $filter => $value)
                <li>
                    <button>
                        <x-pill class="bg-gray-300 px-3 py-1.5 text-sm">{{ $filter }}:
                            {{ $value }}</x-pill>
                    </button>
                </li>
            @endforeach
        </ul>

        <a class="ms-auto rounded-full p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-800"
            href="{{ route('rides.create') }}">
            <x-fas-plus class="size-8" />
        </a>
    </div>

    {{-- Rides list --}}
    @if ($rides->count())
        <ol class="grid auto-rows-fr grid-cols-1 gap-4">
            @foreach ($rides as $ride)
                <li>
                    <x-modals.ride.trigger :$ride />
                </li>
            @endforeach
        </ol>

        <x-modals.ride />

        {{ $rides->links() }}
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
