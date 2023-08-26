<main class="container-fluid d-flex flex-column mt-3">
    <a href="/newride" class="btn btn-uva-ob fw-bold fs-5 mb-4 p-2" role="button">Post new ride</a>
    <?php if (isset($_SESSION['error_msg'])) { ?>
        <p class="alert alert-danger"><?= $_SESSION['error_msg'] ?></p>
    <?php
        unset($_SESSION['error_msg']);
    } else if (isset($_SESSION['success_msg'])) { ?>
        <p class="alert alert-success"><?= $_SESSION['success_msg'] ?></p>
    <?php
        unset($_SESSION['success_msg']);
    } ?>
    <h2>Posted Rides:</h2>
    <?php if ($posted != null) { ?>
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4 mb-3">
            <?php foreach ($posted as $ride) { ?>
                <div class="col">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= $ride["origin_address"] ?> &#8594; <?= $ride["destination_address"] ?></h5>
                            <h6 class="card-subtitle mb-2"><?= date("n/j \@ g:i a", strtotime($ride["start_time"])) ?></h6>
                            <p class="card-text"><?= $ride["seats_open"] ?> out of <?= $ride["seats_total"] ?> seats left!</p>
                            <button type="button" class="card-link btn btn-uva-ob" data-bs-toggle="modal" data-bs-target="#mapModal" data-modal-type="posted" data-ride="<?= $ride["id"] ?>">More info</button>
                            <a href="/myrides/delete/<?= $ride['id'] ?>" class="card-link btn btn-uva-ob">Delete</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } else { ?>
        <div class="text-center mb-3">
            <h3>You have not posted any rides!</h3>
        </div>
    <?php } ?>

    <h2>Joined Rides:</h2>
    <?php if ($joined != null) { ?>
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
            <?php foreach ($joined as $ride) { ?>
                <div class="col">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= $ride["origin_address"] ?> &#8594; <?= $ride["destination_address"] ?></h5>
                            <h6 class="card-subtitle mb-2"><?= date("n/j \@ g:i a", strtotime($ride["start_time"])) ?></h6>
                            <p class="card-text"><?= $ride["seats_open"] ?> out of <?= $ride["seats_total"] ?> seats left!</p>
                            <button type="button" class="card-link btn btn-uva-ob" data-bs-toggle="modal" data-bs-target="#mapModal" data-modal-type="joined" data-ride="<?= $ride["id"] ?>">More info</button>
                            <a href="/myrides/leave/<?= $ride['id'] ?>" class="card-link btn btn-uva-ob">Leave</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } else { ?>
        <div class="text-center">
            <h3>You have not joined any rides!</h3>
        </div>
    <?php }

    if ($posted != null || $joined != null) {
        require_once "templates/mapmodal.php";
    } ?>
</main>