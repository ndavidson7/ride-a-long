import { RideModalMapComponent } from "@modules/map.js";

const map = new RideModalMapComponent(document.getElementById("map-component"));

const modal = document.querySelector("#mapModal");
modal?.addEventListener("show.bs.modal", (event) => {
    const rideId = event.relatedTarget.dataset.ride; // relatedTarget is the clicked ride card
    const relatedModelId = event.relatedTarget.dataset.relatedModelId;
    const userRelation = event.relatedTarget.dataset.userRelation;

    map.update(rideId);
    updateButton(userRelation, rideId, relatedModelId);
});

function updateButton(userRelation, rideId, relatedModelId) {
    const deleteFormTemplate = document.getElementById("delete-form").content;

    let modalButton;
    switch (userRelation) {
        case "driver":
            modalButton = document.createElement("a");
            modalButton.href = route("rides.edit", rideId);
            modalButton.classList.add("btn", "btn-primary");
            modalButton.textContent = "Edit Ride";
            break;
        case "passenger":
            if ("content" in document.createElement("template")) {
                modalButton = deleteFormTemplate.cloneNode(true);
                modalButton.querySelector("form").action = route(
                    "rideUser.destroy",
                    relatedModelId
                );
                modalButton.querySelector("button").textContent = "Leave Ride";
            } else {
                console.error("HTML template element not supported.");
            }
            break;
        case "requester":
            if ("content" in document.createElement("template")) {
                modalButton = deleteFormTemplate.cloneNode(true);
                modalButton.querySelector("form").action = route(
                    "requests.destroy",
                    relatedModelId
                );
                modalButton.querySelector("button").textContent =
                    "Cancel Request";
            } else {
                console.error("HTML template element not supported.");
            }
            break;
        case "none":
        default:
            modalButton = document.createElement("a");
            modalButton.href = route("requests.create", rideId);
            modalButton.classList.add("btn", "btn-primary");
            modalButton.textContent = "Request to Join";
    }
    document.getElementById("modal-button-div").replaceChildren(modalButton);
}
