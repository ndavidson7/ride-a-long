import "./bootstrap";
import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
import map from "./components/map";

Alpine.data("map", map);

Livewire.start();
