import * as bootstrap from "bootstrap";

const base = window.location.origin;

const notifsEl = document.getElementById("notifs");
const notifsBadge = document.getElementById("notifsBadge");
const numNotifsEl = document.getElementById("numNotifs");
let numNotifs = parseInt(numNotifsEl.textContent);

const toastContainer = document.getElementById("toast-container");

// Listen for any incoming notifications
Echo.private("App.Models.User." + userId).notification((notification) => {
    if (import.meta.env.VITE_APP_DEBUG)
        console.log("Incoming notification:", notification);

    // Show the notification badge
    // notifsBadge.style.display = "inline-block"; // Redundant because notifsBadge is always shown at the moment

    // Extract and format data from the notification
    const data = formatData(notification);

    // Add the notification to the dropdown
    makeNotification(data);

    // Create and show a toast
    makeToast(data);
});

function formatData(notification) {
    const data = notification["data"];
    let message = "";
    switch (notification["type"]) {
        case "App\\Notifications\\RequestCreated":
            const requester = data["user"];
            message = `${requester.first_name} ${requester.last_name} requested to join your ride!`;
            break;
        case "App\\Notifications\\RequestUpdated":
            const driver = data["driver"];
            message = `${driver.first_name} ${driver.last_name} ${
                data["response"] == 1 ? "accepted" : "declined"
            } your request!`;
            break;
        case "App\\Notifications\\RideUserDestroyed":
            const ride = data["ride"];
            const user = data["user"];
            message = `${user.first_name} ${user.last_name} left your ride from ${ride.origin.city} to ${ride.destination.city}!`;
            break;
        default:
            console.error("Unknown type in notification:", notification);
            message = "Unknown notification type";
    }

    const viewUrl = route("notifications.show", notification["id"]);
    const deleteUrl = route("notifications.destroy", notification["id"]);

    return { message, viewUrl, deleteUrl };
}

function makeNotification(data) {
    const deleteButton = document
        .getElementById("notif-delete-form")
        .content.cloneNode(true);
    deleteButton.querySelector("form").action = data.deleteUrl;

    const tempDiv = document.createElement("div");
    tempDiv.appendChild(deleteButton);

    notifsEl.insertAdjacentHTML(
        "afterbegin",
        `<li class="d-flex justify-content-center align-items-center">
    <i class="bi bi-circle-fill text-danger ms-3" style="font-size: 0.5rem"></i>
    <a class="dropdown-item" href="${data.viewUrl}">${data.message} <small class="text-muted">just now</small></a>${tempDiv.innerHTML}</li>`
    );
    numNotifs++;
    numNotifsEl.textContent = numNotifs;
}

function makeToast(data) {
    const toastElement = document
        .getElementById("notif-toast-template")
        .content.cloneNode(true);
    const anchor = toastElement.querySelector("a");
    anchor.href = data.viewUrl;
    anchor.textContent = data.message;

    toastContainer.appendChild(toastElement);

    console.log(toastContainer.lastElementChild);

    // must use lastElementChild because toastElement is a DocumentFragment
    bootstrap.Toast.getOrCreateInstance(toastContainer.lastElementChild).show();
}
