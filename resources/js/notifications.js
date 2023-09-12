const base = window.location.origin;

fetch(`${base}/requests`, { headers: { Accept: "application/json" } })
    .then((response) => response.json())
    .then((data) => {
        const notifs = [];

        for (const request of data) {
            notifs.push(
                `<li><a class="dropdown-item" href="${route(
                    "requests.show",
                    request["id"]
                )}">${request["user"].first_name} ${
                    request["user"].last_name
                } requested to join your ride!</a></li>`
            );
        }

        if (notifs.length == 0) {
            document.getElementById("notifsBadge").remove();
        } else {
            document.getElementById("numNotifs").textContent = notifs.length;
        }
        document.getElementById("notifs").innerHTML = notifs.join("");
    });
