<main class="container-fluid d-flex flex-column mt-3">
    <a href="/newride" class="btn btn-uva-ob fw-bold fs-5 mb-4 p-2" role="button">Post new ride</a>
    @forelse ($rides as $ride)
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $ride['orig_addr'] }} &#8594; {{ $ride['dest_addr'] }}</h5>
                        <h6 class="card-subtitle mb-2">{{ date('n/j \@ g:i a', strtotime($ride['start_time'])) }}</h6>
                        <p class="card-text"> {{ $ride['seats_open'] }} out of {{ $ride['seats_total'] }} seats left!
                        </p>
                        <button type="button" class="card-link btn btn-uva-ob stretched-link" data-bs-toggle="modal"
                            data-bs-target="#mapModal" data-modal-type="info" data-ride="{{ $ride['id'] }}">More
                            info</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center">
            <h3>There are no upcoming rides :(</h3>
            <h4>Be the first to post one!</h4>
        </div>
    @endforelse
    <?php require_once 'templates/mapmodal.php'; ?>
</main>
