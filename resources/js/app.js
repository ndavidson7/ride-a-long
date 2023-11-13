import "./bootstrap";
import * as bootstrap from "bootstrap";

const alertToast = document.getElementById("alert-toast");

if (alertToast) {
    bootstrap.Toast.getOrCreateInstance(alertToast).show();
}
