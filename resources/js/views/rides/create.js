import { RideCreateMapComponent } from "@modules/map.js";
import "@modules/tooltip.js";

const map = new RideCreateMapComponent(
    document.getElementById("map-component")
);

const previewButton = document.getElementById("preview-button");
previewButton.addEventListener("click", () => {
    map.update();
    previewButton.disabled = true;
});

// TODO: Extract this to form.js?
// Enable preview button if all required inputs are filled
const requiredInputs = document.querySelectorAll("[required]");
requiredInputs.forEach((input) => {
    input.addEventListener("change", function () {
        let allFilled = true;
        requiredInputs.forEach((input) => {
            if (input.value === "") {
                allFilled = false;
            }
        });

        previewButton.disabled = !allFilled;
    });
});
