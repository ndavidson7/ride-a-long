<?php

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

// Register the autoloader
spl_autoload_register(function ($classname) {
    require "classes/$classname.php";
});

// Start session
session_start();

// If the user's email is not set in the session, then it's not
// a valid session (they didn't get here from the sign in/sign up page),
// so we should send them over to sign in or sign up first before doing
// anything else! Otherwise, parse the query string for command
$command = "signin";
if (isset($_SESSION["email"])) {
    if (isset($_GET["command"])) {
        $command = $_GET["command"];
    } else {
        # Signed-in users going to the base URL should be redirected to the rides page
        $command = "rides";
    }
} else if (isset($_GET["command"]) and $_GET["command"] == "signup") {
    $command = "signup";
}

$get_vars = array();
foreach ($_GET as $key => $value) {
    if ($key != "command") {
        $get_vars[$key] = $value;
    }
}

// Instantiate the controller and run
$controller = new Controller($command, $get_vars);
$controller->run();
