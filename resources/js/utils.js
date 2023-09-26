/**
 *
 * @param {Date} datetime HTML datetime-local input value or MySQL datetime value
 * @param {*} mysql Whether the datetime value is a MySQL datetime value
 * @returns {Array} [date, time] where date is a string in the format "Month Day, Year" and time is a string in the format "hh:mm AM/PM"
 */
export function formatDateTime(datetime, mysql = false) {
    let d;
    if (mysql) {
        const dateTimeParts = datetime.split(/[- :]/); // regular expression split that creates array with: year, month, day, hour, minutes, seconds values
        dateTimeParts[1]--; // monthIndex begins with 0 for January and ends with 11 for December so we need to decrement by one
        d = new Date(...dateTimeParts); // our Date object
    } else {
        d = new Date(datetime);
    }
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
    return [date, time];
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
