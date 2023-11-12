<x-layouts.main title="Ride listings" :$entries>
    <main
        class="flex-grow-1 @if ($rides->isEmpty()) d-flex justify-content-center align-items-center @else container-fluid py-3 @endif">
        @if ($rides->count())
            <form action="#" method="get" class="mb-3">
                <div class="row justify-content-center align-items-center row-gap-2">
                    <div class="col-auto">
                        {{-- <label class="form-label" for="origin-city">Origin City</label> --}}
                        <input type="text" class="form-control" name="origin-city" id="origin-city"
                            placeholder="Origin City" value="{{ request('origin-city') }}" />
                    </div>
                    <div class="col-auto">
                        {{-- <label class="form-label" for="destination-city">Destination City</label> --}}
                        <input type="text" class="form-control" name="destination-city" id="destination-city"
                            placeholder="Destination City" value="{{ request('destination-city') }}" />
                    </div>
                    <div class="col-auto">
                        <?php date_default_timezone_set('America/New_York'); ?>
                        {{-- <label class="form-label" for="start-date">Date</label> --}}
                        <input type="date" class="form-control" name="start-date" id="start-date"
                            placeholder="Origin City" min="{{ date('Y-m-d') }}" value="{{ request('start-date') }}" />
                    </div>
                    <div class="col-auto form-check">
                        <label class="form-check-label" for="detours-checkbox"><a href="#"
                                data-bs-toggle="tooltip"
                                data-bs-title="If detours are allowed, you can request pickup and/or dropoff locations that are different than the ride's origin and destination">Detours</a>
                            Allowed</label>
                        <input type="checkbox" class="form-check-input" id="detours-checkbox" name="detours"
                            value="1" @if (request('detours')) checked @endif />
                    </div>
                    {{-- <div class="col-auto form-check">
                        <label class="form-check-label" for="exclude-full-checkbox">Exclude Full Rides</label>
                        <input type="checkbox" class="form-check-input" id="exclude-full-checkbox" name="exclude-full"
                            value="1" @if (request('exclude-full')) checked @endif />
                    </div> --}}
                    <div class="col-auto form-check">
                        <label class="form-check-label" for="my-rides-checkbox">My Rides</label>
                        <input type="checkbox" class="form-check-input" id="my-rides-checkbox" name="my-rides"
                            value="1" @if (request('my-rides')) checked @endif />
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-uva-ob fs-6 fw-bold">Filter</button>
                    </div>
                </div>
            </form>
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
                        <x-ride-card :$ride />
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
