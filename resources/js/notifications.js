const base = window.location.origin;

fetch(`${base}/notifications`)
    .then((response) => response.json())
    .then((data) => {
        var notifs = [];

        // Assoc. array of ride IDs to arrays of rider emails, e.g., [1 => ["nid3dhu@virginia.edu"], 2 => ["nid3dhu@virginia.edu", "abc1def@virginia.edu"]]
        for (const [ride, requests] of Object.entries(data["requests"])) {
            requests.forEach((user) => {
                notifs.push(
                    "<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-target='#requestModal' data-ride='" +
                        ride +
                        "' data-user='" +
                        user +
                        "'>Someone requested to join your ride!</button></li>"
                );
            });
        }

        // Assoc. array of ride ID to response, e.g., [1 => 1, 2 => 2]
        for (const [ride, response] of Object.entries(data["responses"])) {
            if (response === 1) {
                notifs.push(
                    "<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-target='#responseModal' data-ride='" +
                        ride +
                        "' data-response=Accepted>Your request was accepted!</button></li>"
                );
            } else {
                notifs.push(
                    "<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-target='#responseModal' data-ride='" +
                        ride +
                        "' data-response=Denied>Your request was denied!</button></li>"
                );
            }
        }

        if (notifs.length == 0) {
            document.getElementById("notifsBadge").remove();
        } else {
            document.getElementById("numNotifs").textContent = notifs.length;
        }
        document.getElementById("notifs").innerHTML = notifs.join("");

        document
            .getElementById("requestModal")
            .addEventListener("show.bs.modal", (event) => {
                // Determine which request was clicked
                const ride = event.relatedTarget.dataset.ride;
                const user = event.relatedTarget.dataset.user;

                fetch(
                    `${base}/index.php?command=requestinfo&ride=${ride}&user=${user}`
                )
                    .then((response) => response.json())
                    .then((data) => {
                        document.getElementById("requestRide").textContent =
                            data.origin_address +
                            " \u2192 " +
                            data.destination_address;
                        document.getElementById("requestUser").textContent =
                            data.rider.first_name +
                            " " +
                            data.rider.last_name +
                            " (" +
                            data.rider.email +
                            ")";
                    });

                document.getElementById("deny").href =
                    base +
                    "/index.php?command=respond&ride=" +
                    ride +
                    "&user=" +
                    user +
                    "&response=0";
                document.getElementById("accept").href =
                    base +
                    "/index.php?command=respond&ride=" +
                    ride +
                    "&user=" +
                    user +
                    "&response=1";
            });

        document
            .getElementById("responseModal")
            .addEventListener("show.bs.modal", (event) => {
                const ride = event.relatedTarget.dataset.ride;
                const response = event.relatedTarget.dataset.response;

                fetch(`${base}/rides/${ride}`)
                    .then((response) => response.json())
                    .then((data) => {
                        document.getElementById("responseRide").textContent =
                            data.origin.address +
                            " \u2192 " +
                            data.destination.address;
                        document.getElementById("response").textContent =
                            response;
                    });

                document.getElementById("read").href =
                    base + "/index.php?command=deleteresponse&ride=" + ride;
            });
    });
