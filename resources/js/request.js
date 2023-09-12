const previewButton = document.getElementById("preview-button");
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
