const base = window.location.origin;

const notifsEl = document.getElementById("notifs");
const notifsBadge = document.getElementById("notifsBadge");
const numNotifsEl = document.getElementById("numNotifs");
let numNotifs = parseInt(numNotifsEl.textContent);

const toastContainer = document.getElementById("toast-container");

// Listen for any incoming notifications
Echo.private("App.Models.User." + window.userId).notification(
    (notification) => {
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
    }
);

function formatData(notification) {
    const data = notification["data"];

    const message = data["message"];
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
    <a class="dropdown-item d-flex align-items-center" href="${data.viewUrl}">
    <i class="bi bi-circle-fill text-danger me-2" style="font-size: 0.3rem"></i>
    ${data.message} <small class="text-body-secondary ms-2">just now</small></a>${tempDiv.innerHTML}</li>`
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
    // TODO: Replace now that no longer using bootstrap
    // bootstrap.Toast.getOrCreateInstance(toastContainer.lastElementChild).show();
}
