import mapboxgl from "mapbox-gl";

mapboxgl.accessToken =
    "pk.eyJ1IjoibmRhdmlkc29uNyIsImEiOiJjbG4wa3U2dmkxcXIzMnNxdjFtaXQ0bGx5In0.4V3AYsZuul1TpIOJr1PR2A";
const map = new mapboxgl.Map({
    container: "map", // container ID
    center: [-74.5, 40], // starting position [lng, lat]
    zoom: 9, // starting zoom
});

console.log(map);
