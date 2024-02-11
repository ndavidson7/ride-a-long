import mapboxgl from "mapbox-gl";
const token = import.meta.env.VITE_MAPBOX_ACCESS_TOKEN;

// initialize map
if (document.getElementById("map")) {
    mapboxgl.accessToken = token;
    const map = new mapboxgl.Map({
        container: "map", // container ID
        center: [-74.5, 40], // starting position [lng, lat]
        zoom: 9, // starting zoom
    });
}
