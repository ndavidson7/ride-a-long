<x-layouts.app class="space-y-3" title="Ride listings" :$entries>

    <x-ride-filter />

    @if ($rides->count())
        <div class="grid auto-rows-fr grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
            <div class="rounded border shadow-lg">
                <a class="size-full grid place-items-center text-blue-500 hover:text-blue-700"
                    href="{{ route('rides.create') }}">
                    <x-fas-circle-plus class="size-16" /></a>
            </div>

            @foreach ($rides as $ride)
                <x-cards.ride.preview :$ride />
            @endforeach
        </div>
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
