import { RideShowMapComponent } from "@modules/map.js";

const map = new RideShowMapComponent(document.getElementById("map-component"));

// MESSAGING

const messageForm = document.getElementById("message-form");
messageForm.addEventListener("submit", handleSubmit);

const messageHistory = document.getElementById("message-history");
const messageWrapperTemplateOther = document.getElementById(
    "message-wrapper-template-other"
);
const messageWrapperTemplateSelf = document.getElementById(
    "message-wrapper-template-self"
);
const messageTemplateOther = document.getElementById("message-template-other");
const messageTemplateSelf = document.getElementById("message-template-self");
const dividerTemplate = document.getElementById("divider-template");

let lastSender = null,
    lastMessageWrapper = null;

async function handleSubmit(event) {
    event.preventDefault();

    const data = new FormData(event.target);
    const response = await fetch(event.target.action, {
        method: "POST",
        body: data,
        headers: { Accept: "application/json" },
    });

    if (response.ok) {
        // Add message to chat
        addMessage(data.get("message"), window.userId);

        // Clear message input
        messageForm.reset();
    } else {
        const data = await response.json();
        alert(data.message ?? "Something went wrong.");
    }
}

// TODO: Dividers
// TODO: Only update timestamp if time difference is under a certain threshold, otherwise add a new timestamp
function addMessage(message, sender) {
    // if new sender, create new message wrapper
    if (sender !== lastSender) {
        lastMessageWrapper =
            sender === window.userId
                ? messageWrapperTemplateSelf.content.cloneNode(true)
                : messageWrapperTemplateOther.content.cloneNode(true);
    }

    // create and append new message
    const messageTemplate =
        sender === window.userId
            ? messageTemplateSelf.content.cloneNode(true)
            : messageTemplateOther.content.cloneNode(true);
    messageTemplate.querySelector(".message").textContent = message;
    lastMessageWrapper.querySelector(".message-chain").append(messageTemplate);

    // update picture, name, and timestamp
    if (sender !== lastSender) {
        const pfp = lastMessageWrapper.querySelector("img");
        const user = window.users[sender];
        if (user.pfp_url) pfp.src = user.pfp_url;
        else pfp.parentElement.remove();

        lastMessageWrapper.querySelector(".name").textContent = user.name;
    }
    lastMessageWrapper.querySelector(".timestamp").textContent =
        new Date().toLocaleTimeString([], {
            timeZone: "America/New_York",
            timeStyle: "short",
        });

    // append message wrapper to chat if new sender
    if (sender !== lastSender) {
        messageHistory.append(lastMessageWrapper);
        lastMessageWrapper = messageHistory.lastElementChild; // because it's a DocumentFragment
    }

    lastSender = sender;
}
