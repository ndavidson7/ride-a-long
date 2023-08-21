<main class="container-fluid mt-3">
    <form id="request" class="" method="post">
        <label class="form-label" for="pickup-checkbox">Pickup</label>
        <input type="checkbox" name="pickup-checkbox">
        <div class="autocomplete" id="pickup-div">
            <label class="form-label" for="pickup">Your Pickup Address</label>
            <input type="text" class="place waypoint" name="pickup" id="pickup" value="">
            <input type="hidden" class="address" id="pickup-addr" name="pickup-addr" value="">
            <input type="hidden" class="latitude" id="pickup-lat" name="pickup-lat" value="">
            <input type="hidden" class="longitude" id="pickup-long" name="pickup-long" value="">
        </div>
        <label class="form-label" for="dropoff-checkbox">Dropoff</label>
        <input type="checkbox" name="dropoff-checkbox">
        <div class="autocomplete" id="dropoff-div">
            <label class="form-label" for="dropoff">Your Dropoff Address</label>
            <input type="text" class="place waypoint" name="dropoff" id="dropoff" value="">
            <input type="hidden" class="address" id="dropoff-addr" name="dropoff-addr" value="">
            <input type="hidden" class="latitude" id="dropoff-lat" name="dropoff-lat" value="">
            <input type="hidden" class="longitude" id="dropoff-long" name="dropoff-long" value="">
        </div>
        <button type="submit" class="btn btn-uva-ob">Request</button>
        <button type="button" id="previewbutton" class="btn btn-uva-ob" disabled="true" data-bs-toggle="modal" data-bs-target="#mapModal" data-modal-type="request" data-ride="<?= $ride ?>">Preview</button>
    </form>

    <script type="text/javascript">
        // Hide address inputs and preview button until the user checks the boxes
        const previewButton = document.getElementById('previewbutton');
        previewButton.style.display = 'none';

        document.querySelectorAll('.autocomplete').forEach(autocomplete => {
            autocomplete.style.display = 'none';
            autocomplete.addEventListener('input', () => {
                if (this.value !== '') {
                    previewButton.disabled = false;
                } else {
                    previewButton.disabled = true;
                }
            });
        });

        document.querySelectorAll('input[type=checkbox]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.nextElementSibling.style.display = 'block';
                    this.nextElementSibling.firstElementChild.focus();
                    this.nextElementSibling.firstElementChild.required = true;
                } else {
                    this.nextElementSibling.style.display = 'none';
                    this.nextElementSibling.firstElementChild.required = false;
                }

                if (document.querySelectorAll('input[type=checkbox]:checked').length > 0) {
                    previewButton.style.display = 'block';
                } else {
                    previewButton.style.display = 'none';
                }
            });
        });
    </script>

    <?php
    require_once "templates/mapmodal.php";
    ?>
</main>