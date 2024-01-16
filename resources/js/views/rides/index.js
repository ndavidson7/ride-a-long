import { RideModalMapComponent } from "@modules/map";
import "@modules/tooltip";

const map = new RideModalMapComponent(document.getElementById("map-component"));

const modal = document.querySelector("#mapModal");
modal?.addEventListener("show.bs.modal", (event) => {
    const rideId = event.relatedTarget.dataset.ride; // relatedTarget is the clicked ride card
    const userRelation = event.relatedTarget.dataset.userRelation;
    const relatedModelId = event.relatedTarget.dataset.relatedModelId;

    map.update(rideId);
    updateButtons(userRelation, rideId, relatedModelId);
});

function updateButtons(userRelation, rideId, relatedModelId) {
    const deleteFormTemplate = document.getElementById("delete-form").content;

    const viewRideBtn = document.createElement("a");
    viewRideBtn.href = route("rides.show", rideId);
    viewRideBtn.classList.add("btn", "btn-primary");
    viewRideBtn.textContent = "View Ride";

    let modalButtons = [viewRideBtn];
    switch (userRelation) {
        case "driver":
            const editRideBtn = document.createElement("a");
            editRideBtn.href = route("rides.edit", rideId);
            editRideBtn.classList.add("btn", "btn-primary");
            editRideBtn.textContent = "Edit Ride";
            modalButtons.push(editRideBtn);
            break;
        case "passenger":
            if ("content" in document.createElement("template")) {
                const leaveRideBtn = deleteFormTemplate.cloneNode(true);
                leaveRideBtn.querySelector("form").action = route(
                    "rides.users.destroy",
                    [rideId, relatedModelId]
                );
                leaveRideBtn.querySelector("button").textContent = "Leave Ride";
                modalButtons.push(leaveRideBtn);
            } else {
                console.error("HTML template element not supported.");
            }
            break;
        case "requester":
            if ("content" in document.createElement("template")) {
                const cancelRequestBtn = deleteFormTemplate.cloneNode(true);
                cancelRequestBtn.querySelector("form").action = route(
                    "requests.destroy",
                    relatedModelId
                );
                cancelRequestBtn.querySelector("button").textContent =
                    "Cancel Request";
                modalButtons.push(cancelRequestBtn);
            } else {
                console.error("HTML template element not supported.");
            }
            break;
        case "none":
        default:
            const requestBtn = document.createElement("a");
            requestBtn.href = route("requests.create", rideId);
            requestBtn.classList.add("btn", "btn-primary");
            requestBtn.textContent = "Request to Join";
            modalButtons.push(requestBtn);
    }
    document
        .getElementById("modal-button-div")
        .replaceChildren(...modalButtons);
}
