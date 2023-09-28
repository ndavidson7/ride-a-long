import { RequestCreateMapComponent } from "@modules/map.js";

const map = new RequestCreateMapComponent(
    document.getElementById("map-component")
);

const previewButton = document.getElementById("preview-button");
previewButton.addEventListener("click", () => {
    previewButton.disabled = true;
    map.update();
});

const addresses = document.querySelectorAll(".address");
addresses.forEach((address) => {
    address.addEventListener("change", function () {
        // Disable preview button if both addresses are empty
        if (addresses[0].value === "" && addresses[1].value === "") {
            previewButton.disabled = true;
        } else {
            previewButton.disabled = false;
        }
    });
});
