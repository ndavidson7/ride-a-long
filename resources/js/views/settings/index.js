const locationSwitch = document.getElementById("location-switch");
const locationSwitchError = document.getElementById("location-switch-error");

navigator.permissions.query({ name: "geolocation" }).then((result) => {
    if (result.state === "granted") {
        locationSwitch.checked = true;
    }

    result.addEventListener("change", () => {
        if (result.state === "granted") {
            locationSwitch.checked = true;
            locationSwitch.classList.remove("is-invalid");
        }
    });
});

locationSwitch.addEventListener("change", function () {
    if (this.checked) {
        navigator.geolocation.getCurrentPosition(
            async function (position) {
                // Send the position to the server
                // const response = await fetch(route(), {
                //     method: "POST",
                //     body: JSON.stringify({
                //         latitude: position.coords.latitude,
                //         longitude: position.coords.longitude,
                //     }),
                //     headers: { Accept: "application/json" },
                // });
            },
            function (error) {
                locationSwitch.checked = false;
                locationSwitchError.textContent =
                    error.code === 1
                        ? "You have denied permission to access your location. Please enable it in your browser settings to use this feature."
                        : "An error occurred while trying to get your location.";
                locationSwitch.classList.add("is-invalid");
            }
        );
    }
});
