<div class="card text-center h-100">
    <h5 class="card-header">{{ $ride->origin->city }}, {{ $ride->origin->state }}
        &#8594;
        {{ $ride->destination->city }}, {{ $ride->destination->state }}
    </h5>
    <div class="card-body d-flex flex-column">
        {{-- <h5 class="card-title">{{ $ride->origin->city }}, {{ $ride->origin->state }} &#8594;
                                {{ $ride->destination->city }}, {{ $ride->destination->state }}
                            </h5> --}}
        <h6 class="card-subtitle mb-2">{{ date('n/j \@ g:i a', strtotime($ride->start_time)) }}
        </h6>
        <h6 class="card-subtitle mb-2"> {{ $ride->seats_open }} out of {{ $ride->seats_total }}
            seats
            left!
        </h6>
        <h6 class="card-subtitle mb-2">
            Detours Allowed: {{ $ride->detours_allowed ? 'Yes' : 'No' }}
        </h6>
        <p class="card-text mt-auto">{{ $ride->description }}</p>
        <h6 class="card-subtitle mt-auto">{{ $ride->driver->first_name }}
            {{ $ride->driver->last_name }}
        </h6>
    </div>
    <div class="card-footer">
        <button type="button" class="card-link btn btn-uva-ob stretched-link" data-bs-toggle="modal"
            data-bs-target="#mapModal" data-ride="{{ $ride->id }}" data-user-relation="{{ $ride->user_relation }}"
            data-related-model-id="{{ $ride->related_model_id }}">More
            info</button>
    </div>
</div>
