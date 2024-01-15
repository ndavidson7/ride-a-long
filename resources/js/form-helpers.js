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
    const forms = document.querySelectorAll("form[disabled]");
    forms.forEach((form) => {
        const submit = form.querySelector("button[type=submit]");
        submit.disabled = true; // immediately disable in case this was forgotten in the HTML

        const requiredInputs = document.querySelectorAll("[required]");
        requiredInputs.forEach((input) => {
            // on any input, check whether all required inputs have become valid
            input.addEventListener("input", function () {
                let allValid = true;
                requiredInputs.forEach((input) => {
                    if (!input.checkValidity()) {
                        allValid = false;
                    }
                });

                submit.disabled = !allValid;
            });

            // check if input has invalid feedback div
            const error =
                input.parentElement.querySelector(".invalid-feedback");

            if (!error) {
                return;
            }

            // if so, add event listener to show error message
            input.addEventListener("change", function () {
                this.classList.toggle("is-invalid", !this.checkValidity());
                error.textContent = this.validationMessage || "";
            });
        });
    });
}
