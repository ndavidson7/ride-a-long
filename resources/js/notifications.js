import TimeAgo from "javascript-time-ago";
import en from "javascript-time-ago/locale/en";

TimeAgo.addDefaultLocale(en);
const timeAgo = new TimeAgo("en-US");

const base = window.location.origin;

const notifsEl = document.getElementById("notifs");
const notifsBadge = document.getElementById("notifsBadge");
const numNotifsEl = document.getElementById("numNotifs");
let numNotifs = parseInt(numNotifsEl.textContent);

// First, fetch any unread database notifications...
// fetch(`${base}/notifications`, { headers: { Accept: "application/json" } })
//     .then((response) => response.json())
//     .then((data) => {
//         if (import.meta.env.VITE_APP_DEBUG)
//             console.log("Notifications data:", data);

//         for (const notification of data) {
//             notifsEl.insertAdjacentHTML(
//                 "beforeend",
//                 makeNotification(notification)
//             );
//             numNotifs++;
//         }

//         if (numNotifs == 0) {
//             // hide notifsBadge by setting display to none
//             notifsBadge.style.display = "none";
//         } else {
//             numNotifsEl.textContent = numNotifs;
//         }
//     });

// ...then, listen for any new notifications
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

    if (notification["type"] === "App\\Notifications\\RequestCreated") {
        const user = data["user"];
        text = `${user.first_name} ${user.last_name} requested to join your ride!`;
    } else {
        const driver = data["driver"];
        text = `${driver.first_name} ${driver.last_name} ${
            data["response"] == 1 ? "accepted" : "declined"
        } your request!`;
    }

    return `<li><a class="dropdown-item" href="${route(
        "requests.show",
        data["id"]
    )}">${text} <span class="text-muted">${timeAgo.format(
        new Date(notification["created_at"])
    )}</span></a></li>`;
}
