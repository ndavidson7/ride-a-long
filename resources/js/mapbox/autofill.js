import { autofill } from "@mapbox/search-js-web";
import { token } from "./config";

console.log("Autofill loaded");

// initialize autofill
const collection = autofill({
    accessToken: token,
    options: { country: "us", limit: 5, proximity: "ip", streets: false },
});

console.log(collection);

collection.addEventListener("retrieve", (event) => {
    const featureCollection = event.detail;
    const inputEl = event.target;
    console.log(featureCollection);
});
