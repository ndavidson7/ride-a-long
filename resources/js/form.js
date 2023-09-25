export function enableFormSubmitOnInput() {
    const form = document.querySelector(".form-disabled");
    const submit = form.querySelector("button[type=submit]");
    // TODO: Save each input's original value, and check if it has changed.
    // (in the event that the user reverts any changes made before refreshing or submitting)
    form.querySelectorAll("input, select, textarea").forEach((item) =>
        item.addEventListener("change", () => (submit.disabled = false))
    );
}

enableFormSubmitOnInput();
