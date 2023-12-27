<x-layouts.main title="Ride listings" :$entries>
    <main
        class="flex-grow-1 @if ($rides->isEmpty()) d-flex justify-content-center align-items-center @else container-fluid py-3 @endif">
        @if ($rides->count())
            <x-ride-filter />
            <hr class="border-2">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-3">
                <div class="col">
                    <div class="card text-center h-100">
                        <div class="card-body d-flex flex-column">
                            <a href="{{ route('rides.create') }}"
                                class="stretched-link my-auto orange orange-darken-hover" title="Create new ride"><i
                                    class="bi bi-plus-circle-fill" title="Plus icon" aria-hidden="true"
                                    style="font-size: 5em;"></i></a>
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
            <x-modals.ride />
        @else
            <div class="text-center">
                <h3>There are no upcoming rides :(</h3>
                <h4>Be the first to <a href="{{ route('rides.create') }}">post</a> one!</h4>
            </div>
        @endif
    </main>
</x-layouts.main>
