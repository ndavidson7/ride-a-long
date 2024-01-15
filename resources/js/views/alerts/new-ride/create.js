import { NewRideAlertCreateMapComponent } from "@modules/map";

const map = new NewRideAlertCreateMapComponent(
    document.getElementById("map-component")
);

const previewButton = document.getElementById("preview-button");
previewButton.addEventListener("click", () => {
    map.update();
    previewButton.disabled = true;
});

// Submit button is disabled until all required inputs are filled,
// so we can simply check if it is enabled.
const submit = document.querySelector("form[disabled] button[type=submit]");
const requiredInputs = document.querySelectorAll("[required]");
requiredInputs.forEach((input) => {
    input.addEventListener("change", function () {
        previewButton.disabled = submit.disabled;
    });
});

const originRadiusSlider = document.getElementById("origin-radius-slider");
const originRadiusInput = document.getElementById("origin-radius");
const destinationRadiusSlider = document.getElementById(
    "destination-radius-slider"
);
const destinationRadiusInput = document.getElementById("destination-radius");

originRadiusSlider.addEventListener("input", function () {
    originRadiusInput.value = this.value;
    originRadiusInput.dispatchEvent(new Event("change"));
});

destinationRadiusSlider.addEventListener("input", function () {
    destinationRadiusInput.value = this.value;
    destinationRadiusInput.dispatchEvent(new Event("change"));
});

originRadiusInput.addEventListener("input", function () {
    originRadiusSlider.value = this.value;
});

destinationRadiusInput.addEventListener("input", function () {
    destinationRadiusSlider.value = this.value;
});
