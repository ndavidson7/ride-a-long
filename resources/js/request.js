// Hide address inputs and preview button until the user checks the boxes
const previewButton = document.getElementById("preview-button");
previewButton.style.display = "none";

document.querySelectorAll(".autocomplete").forEach((autocomplete) => {
    autocomplete.style.display = "none";
    autocomplete.querySelector(".place").addEventListener("input", (event) => {
        if (event.target.value !== "") {
            previewButton.disabled = false;
        } else {
            previewButton.disabled = true;
        }
    });
});

document.querySelectorAll("input[type=checkbox]").forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
        const autocompleteDiv = this.nextElementSibling;
        const placeInput = autocompleteDiv.firstElementChild;

        if (this.checked) {
            autocompleteDiv.style.display = "block";

            placeInput.focus();
            placeInput.required = true;
        } else {
            autocompleteDiv.style.display = "none";
            placeInput.required = false;

            placeInput.nextElementSibling.value = ""; // reset hidden address input
        }

        if (
            document.querySelectorAll("input[type=checkbox]:checked").length > 0
        ) {
            previewButton.style.display = "block";
        } else {
            previewButton.style.display = "none";
        }
    });
});
