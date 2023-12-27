<form action="#" method="get" class="mb-3">
    <div class="row justify-content-center align-items-center row-gap-2">
        <div class="col-auto">
            {{-- <label class="form-label" for="origin-city">Origin City</label> --}}
            <input type="text" class="form-control" name="origin-city" id="origin-city" placeholder="Origin City"
                value="{{ request('origin-city') }}" />
        </div>
        <div class="col-auto">
            {{-- <label class="form-label" for="destination-city">Destination City</label> --}}
            <input type="text" class="form-control" name="destination-city" id="destination-city"
                placeholder="Destination City" value="{{ request('destination-city') }}" />
        </div>
        <div class="col-auto">
            {{-- <label class="form-label" for="start-date">Date</label> --}}
            <input type="date" class="form-control" name="start-date" id="start-date" placeholder="Origin City"
                min="{{ Carbon\Carbon::now()->setTimezone('America/New_York')->format('Y-m-d') }}"
                value="{{ request('start-date') }}" />
        </div>
        <div class="col-auto form-check">
            <label class="form-check-label" for="detours-checkbox">Detours Allowed <a href="#"
                    data-bs-toggle="tooltip"
                    data-bs-title="If detours are allowed, you can request pickup and/or dropoff locations that are different than the ride's origin and destination"><i
                        class="bi bi-question-circle"></i></a></label>
            <input type="checkbox" class="form-check-input" id="detours-checkbox" name="detours" value="1"
                @if (request('detours')) checked @endif />
        </div>
        {{-- <div class="col-auto form-check">
                        <label class="form-check-label" for="exclude-full-checkbox">Exclude Full Rides</label>
                        <input type="checkbox" class="form-check-input" id="exclude-full-checkbox" name="exclude-full"
                            value="1" @if (request('exclude-full')) checked @endif />
                    </div> --}}
        <div class="col-auto form-check">
            <label class="form-check-label" for="my-rides-checkbox">My Rides</label>
            <input type="checkbox" class="form-check-input" id="my-rides-checkbox" name="my-rides" value="1"
                @if (request('my-rides')) checked @endif />
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-bold">Filter</button>
        </div>
    </div>
</form>
