/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import Pusher from "pusher-js";
window.Pusher = Pusher;

import Echo from "laravel-echo";
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    wsHost: import.meta.env.VITE_PUSHER_HOST
        ? import.meta.env.VITE_PUSHER_HOST
        : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

// Mapbox
import { token } from "@modules/mapbox/config";
import mapboxgl from "mapbox-gl";
mapboxgl.accessToken = token;
window.mapboxgl = mapboxgl;

import createMapboxDirections from "@mapbox/mapbox-sdk/services/directions";
window.createMapboxDirections = createMapboxDirections;

// Radar
import Radar from "radar-sdk-js";
Radar.initialize(
    import.meta.env.VITE_APP_ENV == "local"
        ? import.meta.env.VITE_RADAR_TEST_API_KEY
        : import.meta.env.VITE_RADAR_LIVE_API_KEY,
);
window.Radar = Radar;

// Day.js
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import localizedFormat from "dayjs/plugin/localizedFormat";
dayjs.extend(relativeTime);
dayjs.extend(localizedFormat);
window.dayjs = dayjs;

// Flatpickr
import flatpickr from "flatpickr";
window.flatpickr = flatpickr;
