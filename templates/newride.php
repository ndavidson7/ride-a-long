<main class="container-fluid mt-3">
    <form id="newride" class="row col-sm-10 col-md-8 col-lg-6 mx-auto" action="/newride" method="post">
        <h2 class="text-center col-12">Ride Details</h2>
        <?php if (isset($error_msg)) { ?>
            <p class="alert alert-danger text-center"><?= $error_msg ?></p>
        <?php } ?>
        <div class="mb-3 col-md-4">
            <?php date_default_timezone_set('America/New_York'); ?>
            <label for="start-time" class="form-label">Date and Time</label>
            <input type="datetime-local" class="form-control" id="start-time" name="start-time" min="<?= date("Y-m-d\TH\:i"); ?>" required>
        </div>
        <div class="mb-3 col-md-4">
            <label for="seats" class="form-label">Seats</label>
            <input type="number" class="form-control" id="seats" name="seats" min="1" required>
        </div>
        <div class="mb-3 col-12 autocomplete">
            <label for="origin" class="form-label">Origin</label>
            <input type="text" class="form-control place" id="origin" name="origin" required>
            <input type="hidden" class="address" id="orig-addr" name="orig-addr" maxlength="255" value="">
            <input type="hidden" class="latitude" id="orig-lat" name="orig-lat" value="">
            <input type="hidden" class="longitude" id="orig-long" name="orig-long" value="">
        </div>
        <div class="mb-3 col-12 autocomplete">
            <label for="destination" class="form-label">Destination</label>
            <input type="text" class="form-control place" id="destination" name="destination" required>
            <input type="hidden" class="address" id="dest-addr" name="dest-addr" maxlength="255" value="">
            <input type="hidden" class="latitude" id="dest-lat" name="dest-lat" value="">
            <input type="hidden" class="longitude" id="dest-long" name="dest-long" value="">
        </div>
        <div class="mb-3 col-12">
            <label class="form-label" for="description">Description/Additional info</label>
            <textarea class="form-control" id="description" name="description" rows=3 maxlength="255"></textarea>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-uva-ob">Post</button>
            <button type="button" class="btn btn-uva-ob" data-bs-toggle="modal" data-bs-target="#mapModal" data-modal-type="preview">Preview</button>
        </div>
    </form>
    <?php
    require_once "templates/mapmodal.php";
    ?>
</main>