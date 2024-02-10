<x-layouts.app class="relative" title="Ride listings" :$entries>
    <x-ride-filter class="absolute left-3 top-3" />

    @if ($rides->count())
        {{-- <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-3">
            <div class="col">
                <div class="card h-100 text-center">
                    <div class="card-body d-flex flex-column">
                        <a class="stretched-link orange orange-darken-hover my-auto" href="{{ route('rides.create') }}"
                            title="Create new ride"><i class="bi bi-plus-circle-fill" title="Plus icon"
                                aria-hidden="true" style="font-size: 5em;"></i></a>
                    </div>
                </div>
            </div>
            @foreach ($rides as $ride)
                <div class="col">
                    <x-cards.ride.preview :$ride />
                </div>
            @endforeach
        </div>
        <div class="row">
            {{ $rides->links() }}
        </div>
        <x-modals.ride /> --}}
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
