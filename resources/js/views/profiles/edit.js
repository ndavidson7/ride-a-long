import { enableFormSubmitOnInput } from "@modules/form.js";

const pfpLabel = document.getElementById("pfp-label");
const pfpField = document.getElementById("pfp");
const pfpInvalidFeedback = document.getElementById("pfp-invalid-feedback");
const deletePfpButton = document.getElementById("delete-pfp-button");
const deletePfpInput = document.getElementById("delete-pfp");

pfpField.onchange = function () {
    if (this.files[0].size > 2097152) {
        this.classList.add("is-invalid");
        pfpInvalidFeedback.innerText = "File size too large.";
        this.value = "";
    } else {
        this.classList.remove("is-invalid");
        pfpInvalidFeedback.innerText = "";
    }
};

deletePfpButton.onclick = function () {
    deletePfpInput.value = "1";
    deletePfpInput.dispatchEvent(new Event("change"));
    pfpLabel.innerHTML = `<i class="bi bi-person-circle" style="font-size: 200px"></i>`;
};

// const addNewButton = document.getElementById("add-new-contact");
// addNewButton.addEventListener("click", addNewContact);

// let newContactsCounter = 1;

// function addNewContact(event) {
//     event.target.insertAdjacentHTML(
//         "beforebegin",
//         `<h4>New Contact ${newContactsCounter}</h4>
//         <div class="col-sm-6 mb-3">
//             <label class="form-label" for="contact${newContactsCounter}-first-name">First Name</label>
//             <input type="text" class="form-control" id="contact${newContactsCounter}-first-name"
//                 name="contact${newContactsCounter}-first-name" maxlength="255" />
//         </div>
//         <div class="col-sm-6 mb-3">
//             <label class="form-label" for="contact${newContactsCounter}-last-name">Last Name</label>
//             <input type="text" class="form-control" id="contact${newContactsCounter}-last-name"
//                 name="contact${newContactsCounter}-last-name" maxlength="255" />
//         </div>
//         <div class="col-sm-6 mb-3">
//             <label class="form-label" for="contact${newContactsCounter}-phone">Phone Number</label>
//             <input type="tel" class="form-control" id="contact${newContactsCounter}-phone"
//                 name="contact${newContactsCounter}-phone" placeholder="No spaces, no dashes (ex: 1112223333)"
//                 pattern="[0-9]{10}" />
//         </div>
//         <div class="col-sm-6 mb-3">
//             <label class="form-label" for="contact${newContactsCounter}-relationship">Relationship</label>
//             <input type="text" class="form-control" id="contact${newContactsCounter}-relationship"
//                 name="contact${newContactsCounter}-relationship" maxlength="63" />
//         </div>`
//     );

//     newContactsCounter++;

//     enableFormSubmitOnInput();
// }
