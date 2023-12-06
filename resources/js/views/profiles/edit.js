import { enableSubmitOnAnyChange } from "@modules/form";
enableSubmitOnAnyChange();

const pfpLabel = document.getElementById("pfp-label");
const pfpField = document.getElementById("pfp");
const pfpInvalidFeedback = document.getElementById("pfp-invalid-feedback");
const deletePfpButton = document.getElementById("delete-pfp-button");
const deletePfpInput = document.getElementById("delete-pfp");

pfpField.addEventListener("change", function () {
    if (this.files[0]?.size > 2097152) {
        this.classList.add("is-invalid");
        pfpInvalidFeedback.innerText = "File size too large.";
        this.value = "";
    } else {
        this.classList.remove("is-invalid");
        pfpInvalidFeedback.innerText = "";
    }
});

if (deletePfpButton) {
    deletePfpButton.addEventListener("click", function () {
        deletePfpInput.value = "1"; // Don't need to unset this if user chooses new PFP after clicking delete because delete occurs before upload in controller
        pfpField.dispatchEvent(new Event("change"));
        pfpLabel.innerHTML = `<i class="bi bi-person-circle" style="font-size: 200px"></i>`;
    });
}
