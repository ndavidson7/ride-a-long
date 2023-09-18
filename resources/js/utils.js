export function formatDateTime(datetime) {
    // This commented-out code is for MySQL datetime format, but Laravel can cast to HTML datetime format
    // const dateTimeParts = datetime.split(/[- :]/); // regular expression split that creates array with: year, month, day, hour, minutes, seconds values
    // dateTimeParts[1]--; // monthIndex begins with 0 for January and ends with 11 for December so we need to decrement by one
    // const d = new Date(...dateTimeParts); // our Date object
    const d = new Date(datetime);
    const month = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];
    const date =
        month[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
    const time = d.toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
        timeZone: "America/New_York",
    });
    return { date, time };
}

export function docReady(fn) {
    // see if DOM is already available
    if (
        document.readyState === "complete" ||
        document.readyState === "interactive"
    ) {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}
