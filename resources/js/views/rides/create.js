import { RideCreateMapComponent } from "@modules/map";
import "@modules/tooltip";
import { enableSubmitOnAllRequiredInputsValid } from "@modules/form";
enableSubmitOnAllRequiredInputsValid();

const map = new RideCreateMapComponent(
    document.getElementById("map-component")
);

const previewButton = document.getElementById("preview-button");
previewButton.addEventListener("click", () => {
    map.update();
    previewButton.disabled = true;
});

// Submit button is disabled until all required inputs are filled,
// so we can simply check if it is enabled.
const submit = document.querySelector(
    "form.disabled-until-required button[type=submit]"
);
const requiredInputs = document.querySelectorAll("[required]");
requiredInputs.forEach((input) => {
    input.addEventListener("change", function () {
        previewButton.disabled = submit.disabled;
    });
});
