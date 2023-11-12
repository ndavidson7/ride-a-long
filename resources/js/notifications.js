import TimeAgo from "javascript-time-ago";
import en from "javascript-time-ago/locale/en";

TimeAgo.addDefaultLocale(en);
const timeAgo = new TimeAgo("en-US");

const base = window.location.origin;

const notifsEl = document.getElementById("notifs");
const notifsBadge = document.getElementById("notifsBadge");
const numNotifsEl = document.getElementById("numNotifs");
let numNotifs = parseInt(numNotifsEl.textContent);

// Listen for any incoming notifications
Echo.private("App.Models.User." + userId).notification((notification) => {
    if (import.meta.env.VITE_APP_DEBUG)
        console.log("Incoming notification:", notification);

    notifsBadge.style.display = "inline-block";

    notifsEl.insertAdjacentHTML("afterbegin", makeNotification(notification));
    numNotifs++;
    numNotifsEl.textContent = numNotifs;
});

function makeNotification(notification) {
    const data = notification["data"];
    let text = "";

    switch (notification["type"]) {
        case "App\\Notifications\\RequestCreated":
            const requester = data["user"];
            text = `${requester.first_name} ${requester.last_name} requested to join your ride!`;
            break;
        case "App\\Notifications\\RequestUpdated":
            const driver = data["driver"];
            text = `${driver.first_name} ${driver.last_name} ${
                data["response"] == 1 ? "accepted" : "declined"
            } your request!`;
            break;
        case "App\\Notifications\\RideUserDestroyed":
            const ride = data["ride"];
            const user = data["user"];
            text = `${user.first_name} ${user.last_name} left your ride from ${ride.origin.city} to ${ride.destination.city}!`;
            break;
    }

    const deleteButton = document
        .getElementById("notif-delete-form")
        .content.cloneNode(true);
    deleteButton.querySelector("form").action = route(
        "notifications.destroy",
        notification["id"]
    );

    const tempDiv = document.createElement("div");
    tempDiv.appendChild(deleteButton);

    return `<li class="d-flex justify-content-center align-items-center">
    <i class="bi bi-circle-fill text-danger ms-3" style="font-size: 0.5rem"></i>
    <a class="dropdown-item" href="${route(
        "notifications.show",
        notification["id"]
    )}">${text} <small class="text-muted">${timeAgo.format(
        new Date(notification["created_at"])
    )}</small></a>${tempDiv.innerHTML}</li>`;
}
