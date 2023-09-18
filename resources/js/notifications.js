const base = window.location.origin;

fetch(`${base}/requests`, { headers: { Accept: "application/json" } })
    .then((response) => response.json())
    .then((data) => {
        if (import.meta.env.VITE_APP_DEBUG)
            console.log("Notifications data:", data);

        const notifs = [];

        for (const request of data) {
            notifs.push(makeNotification(request));
        }

        if (notifs.length == 0) {
            document.getElementById("notifsBadge").remove();
        } else {
            document.getElementById("numNotifs").textContent = notifs.length;
        }
        document.getElementById("notifs").innerHTML = notifs.join("");
    });

function makeNotification(request) {
    const response =
        "response" in request
            ? request["response"] == 1
                ? "accepted"
                : "declined"
            : null;

    const text = response
        ? `${request["driver"].first_name} ${request["driver"].last_name} ${response} your request!`
        : `${request["user"].first_name} ${request["user"].last_name} requested to join your ride!`;

    return `<li><a class="dropdown-item" href="${route(
        "requests.show",
        request["id"]
    )}">${text}</a></li>`;
}
