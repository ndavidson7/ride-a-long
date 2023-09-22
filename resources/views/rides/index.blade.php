<x-layouts.main title="Ride listings" :$entries>
    <main class="container-fluid d-flex flex-column mt-3">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <a href="{{ route('rides.create') }}" class="stretched-link" title="Create new ride"><i
                                class="bi bi-plus-circle-fill" title="Plus icon" aria-hidden="true"
                                style="font-size: 5em;"></i></a>
                    </div>
                </div>
            </div>
            @forelse ($rides as $ride)
                <div class="col">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $ride->origin->city }}, {{ $ride->origin->state }} &#8594;
                                {{ $ride->destination->city }}, {{ $ride->destination->state }}
                            </h5>
                            <h6 class="card-subtitle mb-2">{{ date('n/j \@ g:i a', strtotime($ride->start_time)) }}
                            </h6>
                            <p class="card-text"> {{ $ride->seats_open }} out of {{ $ride->seats_total }} seats
                                left!
                            </p>
                            <button type="button" class="card-link btn btn-uva-ob stretched-link"
                                data-bs-toggle="modal" data-bs-target="#mapModal" data-ride="{{ $ride->id }}"
                                data-user-relation="{{ $ride->user_relation }}"
                                data-related-model-id="{{ $ride->related_model_id }}">More
                                info</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center">
                    <h3>There are no upcoming rides :(</h3>
                    <h4>Be the first to post one!</h4>
                </div>
            @endforelse
        </div>
        <x-modals.ride />
    </main>
</x-layouts.main>
