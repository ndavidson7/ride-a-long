function getOrCreateModalButton(modal, type) {
    var element = document.getElementById("modalbutton");
    if (element == null) {
        switch (type) {
            case "info":
                element = document.createElement("a");
                element.role = "button";
                element.textContent = "Request";
                break;
            case "preview":
                element = document.createElement("button");
                element.type = "submit";
                element.form = "newride";
                element.textContent = "Post";
                break;
            case "request":
                element = document.createElement("button");
                element.type = "submit";
                element.form = "request";
                element.textContent = "Confirm";
                break;
            case "posted":
                element = document.createElement("a");
                element.role = "button";
                element.textContent = "Delete";
                break;
            case "joined":
                element = document.createElement("a");
                element.role = "button";
                element.textContent = "Leave";
                break;
            default:
                console.error(`initModal invoked with invalid modal type: ${type}`);
        }
        element.id = "modalbutton";
        element.classList.add("btn", "btn-primary");
        modal.querySelector(".modal-footer").appendChild(element);
    }
    return element;
}

function formatDateTime(datetime) {
    // Source: https://itnext.io/create-date-from-mysql-datetime-format-in-javascript-912111d57599
    const dateTimeParts = datetime.split(/[- :]/); // regular expression split that creates array with: year, month, day, hour, minutes, seconds values
    dateTimeParts[1]--; // monthIndex begins with 0 for January and ends with 11 for December so we need to decrement by one
    const d = new Date(...dateTimeParts); // our Date object
    const month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const date = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    const time = d.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit", hour12: true, timeZone: 'America/New_York' });
    return { date, time };
}