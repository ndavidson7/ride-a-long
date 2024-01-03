export function enableSubmitOnAnyChange() {
    const forms = document.querySelectorAll(".disabled-until-change");
    // TODO: Save each input's original value, and check if it has changed.
    // (in the event that the user reverts any changes made before refreshing or submitting)
    forms.forEach((form) => {
        const submit = form.querySelector("button[type=submit]");
        form.querySelectorAll("input, select, textarea").forEach((item) =>
            item.addEventListener("change", () => (submit.disabled = false))
        );
    });
}

export function enableSubmitOnAllRequiredInputsValid() {
    const forms = document.querySelectorAll(".disabled-until-required");
    forms.forEach((form) => {
        const submit = form.querySelector("button[type=submit]");
        const requiredInputs = document.querySelectorAll("[required]");
        requiredInputs.forEach((input) => {
            input.addEventListener("input", function () {
                let allValid = true;
                requiredInputs.forEach((input) => {
                    if (!input.checkValidity()) {
                        allValid = false;
                    }
                });

                submit.disabled = !allValid;
            });
        });
    });
}

// TODO: Consider changing to a per-input class, rather than per-form
export function validateInputsOnChange() {
    const forms = document.querySelectorAll(".validate-on-change");
    forms.forEach((form) => {
        const inputs = form.querySelectorAll("input, select, textarea");
        inputs.forEach((input) => {
            const error =
                input.parentElement.querySelector(".invalid-feedback");
            input.addEventListener("change", function () {
                if (!this.checkValidity()) {
                    this.classList.add("is-invalid");
                    error.textContent = this.validationMessage;
                } else {
                    this.classList.remove("is-invalid");
                    error.textContent = "";
                }
            });
        });
    });
}
