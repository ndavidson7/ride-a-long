import { RideShowMapComponent } from "@modules/map";
import dayjs from "dayjs";
import calendar from "dayjs/plugin/calendar";
dayjs.extend(calendar);

const map = new RideShowMapComponent(document.getElementById("map-component"));

// MESSAGING

const messageForm = document.getElementById("message-form");
messageForm.addEventListener("submit", handleSubmit);

const messageHistory = document.getElementById("message-history");
messageHistory.scrollTop =
    messageHistory.scrollHeight - messageHistory.clientHeight; // immediately scroll to bottom

const messageWrapperTemplateOther = document.getElementById(
    "message-wrapper-template-other"
);
const messageWrapperTemplateSelf = document.getElementById(
    "message-wrapper-template-self"
);
const messageTemplateOther = document.getElementById("message-template-other");
const messageTemplateSelf = document.getElementById("message-template-self");
const dividerTemplate = document.getElementById("divider-template");

let lastMessageWrapper = messageHistory.lastElementChild;
let lastSenderId = lastMessageWrapper?.dataset.senderId;

// add event listener to all messages so that the timestamp invisible class is toggled off
document.querySelectorAll(".message").forEach((message) => {
    addMessageEventListeners(message);
});

Echo.private(`mc-chat-conversation.${ride.conversation.id}`).listen(
    ".Musonza\\Chat\\Eventing\\MessageWasSent",
    (e) => {
        if (e.message.sender.id != window.userId)
            addMessage(e.message.body, e.message.sender);
    }
);

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
        addMessage(data.get("message"), { id: window.userId });

        // Clear message input
        messageForm.reset();
    } else {
        const data = await response.json();
        alert(data.message ?? "Something went wrong.");
    }
}

/**
 * Adds a message to the chat history
 *
 * @param {string} message The message to add, strings only for now
 * @param {object} sender User sending the message, with the following properties: id, name, pfp_url (if user is self, only id is required)
 */
function addMessage(message, sender) {
    // if is at bottom prior to adding message, scroll to bottom after adding message
    const isAtBottom =
        messageHistory.scrollTop ==
        messageHistory.scrollHeight - messageHistory.clientHeight;

    // if new sender, create new message wrapper, update picture, name, and calendar timestamp, and append to chat history
    if (sender.id != lastSenderId) {
        lastMessageWrapper =
            sender.id == window.userId
                ? messageWrapperTemplateSelf.content.cloneNode(true)
                : messageWrapperTemplateOther.content.cloneNode(true);

        // if sender is not self (because messageWrapperTemplateSelf has all this data already)
        if (sender.id != window.userId) {
            const pfpElement = lastMessageWrapper.querySelector("img");
            if (sender.pfp_url != null) {
                pfpElement.src = sender.pfp_url;
                pfpElement.alt = `${sender.name}'s profile picture`;
                pfpElement.parentElement.href = route("users.show", sender.id);
            } else pfpElement.parentElement.remove();

            const nameElement = lastMessageWrapper.querySelector(".name");
            nameElement.href = route("users.show", sender.id);
            nameElement.textContent = sender.name;
        }

        lastMessageWrapper.querySelector(".calendar").textContent =
            dayjs().calendar();

        messageHistory.appendChild(lastMessageWrapper);
        lastMessageWrapper = messageHistory.lastElementChild; // because it's a DocumentFragment
    }

    // create new message
    const messageTemplate =
        sender.id == window.userId
            ? messageTemplateSelf.content.cloneNode(true)
            : messageTemplateOther.content.cloneNode(true);
    messageTemplate.querySelector(".message").textContent = message;
    messageTemplate.querySelector(".timestamp").textContent =
        new Date().toLocaleTimeString([], { timeStyle: "short" });

    // add event listeners
    addMessageEventListeners(messageTemplate.querySelector(".message"));

    // append message
    lastMessageWrapper
        .querySelector(".message-chain")
        .appendChild(messageTemplate);

    if (isAtBottom) {
        messageHistory.scrollTop =
            messageHistory.scrollHeight - messageHistory.clientHeight;
    }

    // update last sender
    lastSenderId = sender.id;
}

function addMessageEventListeners(message) {
    message.addEventListener("mouseenter", (event) => {
        event.target.parentElement
            .querySelector(".timestamp")
            .classList.remove("invisible");
    });
    message.addEventListener("mouseleave", (event) => {
        event.target.parentElement
            .querySelector(".timestamp")
            .classList.add("invisible");
    });
}
