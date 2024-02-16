import mapboxgl from "mapbox-gl";
import { token } from "./config";

// initialize map
if (document.getElementById("map")) {
    mapboxgl.accessToken = token;
    const map = new mapboxgl.Map({
        container: "map", // container ID
        center: [-74.5, 40], // starting position [lng, lat]
        zoom: 9, // starting zoom
    });
}
