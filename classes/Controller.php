<?php
class Controller
{
    private $command;
    private $db;
    private $get_vars;
    private $user;
    private const NOTIFS = '<script src="/scripts/notifications.js" charset="utf-8" defer></script>';
    private const UTILS = '<script src="/scripts/utils.js" charset="utf-8"></script>';
    private const GOOGLE_API = '<script src="/scripts/google-api.js" charset="utf-8"></script>';
    private const MAPS_PLACES = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAIlNof-TwR5KfntgTBOWjxDcBV-mqNAnc&libraries=places";
    private const MAPS_AUTOCOMPLETE_AND_PLACES = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAIlNof-TwR5KfntgTBOWjxDcBV-mqNAnc&callback=initAutocomplete&libraries=places";

    public function __construct($command, $get_vars)
    {
        $this->command = $command;
        $this->db = new Database();
        $this->get_vars = $get_vars;
        $this->user = $_SESSION["email"] ?? null;
    }

    public function run()
    {
        switch ($this->command) {
            case "notifications":
                $this->getNotifs();
                break;
            case "requestinfo":
                $this->requestInfo();
                break;
            case "rides":
                $this->allRides();
                break;
            case "newride":
                $this->newRide();
                break;
            case "profile":
                $this->profile();
                break;
            case "messages":
                $this->messages();
                break;
            case "deleteaccount":
                $this->deleteAccount();
                break;
            case "myrides":
                $this->myRides();
                break;
            case "deleteride":
                $this->deleteRide();
                break;
            case "leaveride":
                $this->leaveRide();
                break;
            case "rideinfo":
                $this->rideInfo();
                break;
            case "request":
                $this->request();
                break;
            case "respond":
                $this->respond();
                break;
            case "deleteresponse":
                $this->deleteResponse();
                break;
            case "report":
                $this->report();
                break;
            case "signup":
                $this->signUp();
                break;
            case "signout":
                $this->destroySession();
            case "signin":
            default:
                $this->signIn();
                break;
        }
    }

    // Destroy the session
    private function destroySession()
    {
        session_destroy();
    }

    /* 
   * My simple templating system.
   * $template is the name of the template to be rendered (without the .php extension).
   * templates should only include the <main> tag and its children.
   * $navbar is a boolean indicating whether the navbar or the largeheader should be rendered.
   * $vars is an associative array of variables to be passed to the template.
   */
    private function renderTemplate($template, $navbar, $vars)
    {
        extract($vars);
        require "templates/top.php";
        require "templates/$template.php";
        require "templates/bottom.php";
    }

    // Get number of seats available for a given ride. Returns -1 if ride does not exist.
    private function calculateSeatsOpen($ride_id)
    {
        $seats_total = $this->db->query("select seats_total from ride where id = ?;", "i", $ride_id);
        $num_riders = $this->db->query("select count(rider_email) from ride_riders where id = ?;", "i", $ride_id);
        if ($seats_total === false || $num_riders === false) {
            die("Error retrieving data.");
        } else if (empty($seats_total)) {
            // die("Ride not found.");
            return -1; // can be used to check if ride exists
        }
        return $seats_total[0]["seats_total"] - $num_riders[0]["count(rider_email)"];
    }

    // Sign in template and logic
    public function signIn()
    {
        if (isset($_POST["email"])) {
            $data = $this->db->query("select password from user where email = ?;", "s", $_POST["email"]);
            if ($data === false) {
                $error_msg = "Error checking for user.";
            } else if (!empty($data)) {
                if (password_verify($_POST["password"], $data[0]["password"])) {
                    $_SESSION["email"] = $_POST["email"];
                    header("Location: /rides");
                } else {
                    $error_msg = "Wrong password.";
                }
            } else { // empty, no user found
                $error_msg = "No user found with given email.";
            }
        }

        $title = "Sign in";
        $styles = array("largeheader", "signin");
        $vars = array("title" => $title, "styles" => $styles);
        if (isset($error_msg)) {
            $vars["error_msg"] = $error_msg;
        }
        $this->renderTemplate("signin", false, $vars);
    }

    // Sign up form template and logic
    public function signUp()
    {
        // Validate inputs
        // No input can be empty due to the required attribute on their HTML inputs
        // Emails are validated by their HTML pattern attribute (TODO: sign up/in through Google/Netbadge)
        if (isset($_POST["email"])) {
            if ($_POST["password"] != $_POST["password2"]) {
                $error_msg = "Passwords do not match!";
            } else if (!empty($this->db->query("select * from user where email = ?;", "s", $_POST["email"]))) {
                $error_msg = "Email already in use! Try signing in instead.";
            } else {
                $insert = $this->db->query("insert into user (email, password, first_name, last_name, phone) values (?, ?, ?, ?, ?);", "sssss", $_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT), $_POST["first_name"], $_POST["last_name"], $_POST["phone"]);
                if ($insert === false) {
                    $error_msg = "Error inserting user.";
                } else {
                    $_SESSION["email"] = $_POST["email"];
                    header("Location: /profile");
                }
            }
        }

        $title = "Sign up";
        $styles = array("largeheader", "signup");
        $vars = array("title" => $title, "styles" => $styles);
        if (isset($error_msg)) {
            $vars["error_msg"] = $error_msg;
        }
        $this->renderTemplate("signup", false, $vars);
    }

    // Ride listings template and logic
    public function allRides()
    {
        $rides = $this->db->query("select * from ride;");
        if ($rides === false) {
            $error_msg = "Error checking for rides.";
        }
        foreach ($rides as &$ride) {
            $seats_open = $this->calculateSeatsOpen($ride["id"]);
            $ride["seats_open"] = $seats_open;
            unset($ride);
        }

        if (!empty($this->get_vars) and array_key_exists("output", $this->get_vars) and $this->get_vars["output"] == "json") {
            header("Content-Type: application/json");
            echo json_encode($rides);
        } else {
            $title = "Ride listings";
            $styles = array("main");
            $scripts = array(self::NOTIFS, self::UTILS, self::GOOGLE_API);
            $maps_script = self::MAPS_PLACES;
            $vars = array("title" => $title, "styles" => $styles, "scripts" => $scripts, "maps_script" => $maps_script, "rides" => $rides);
            if (isset($error_msg)) {
                $vars["error_msg"] = $error_msg;
            }
            $this->renderTemplate("rides", true, $vars);
        }
    }

    // New ride form template and logic
    public function newRide()
    {
        // Check that the user is a driver
        $driver = $this->db->query("select * from driver where email = ?;", "s", $this->user);
        if ($driver === false) {
            $error_msg = "Error checking for driver.";
        } else if (empty($driver)) {
            $error_msg = "You are not a driver! You must upload your car info to create a ride.";
            return $this->profile($error_msg);
        }

        if (isset($_POST["start-time"])) {
            $orig_coords = $this->db->query("select * from coordinates where address = ?;", "s", $_POST["orig-addr"]);
            $dest_coords = $this->db->query("select * from coordinates where address = ?;", "s", $_POST["dest-addr"]);
            if (empty($orig_coords)) $coords_insert1 = $this->db->query("insert into coordinates (address, latitude, longitude) values (?, ?, ?);", "sdd", $_POST["orig-addr"], $_POST["orig-lat"], $_POST["orig-long"]);
            if (empty($dest_coords)) $coords_insert2 = $this->db->query("insert into coordinates (address, latitude, longitude) values (?, ?, ?);", "sdd", $_POST["dest-addr"], $_POST["dest-lat"], $_POST["dest-long"]);
            $ride_insert = $this->db->query("insert into ride (driver_email, start_time, orig_addr, dest_addr, seats_total, description) values (?, ?, ?, ?, ?, ?);", "ssssis", $this->user, $_POST["start-time"], $_POST["orig-addr"], $_POST["dest-addr"], $_POST["seats"], $_POST["description"]);
            if ($coords_insert1 === false or $coords_insert2 === false) {
                $error_msg = "Error inserting coordinates.";
            } else if ($ride_insert === false) {
                $error_msg = "Error inserting ride.";
            } else {
                header("Location: /rides");
            }
        }

        $title = "New ride";
        $styles = array('main');
        $scripts = array(self::NOTIFS, self::UTILS, self::GOOGLE_API);
        $maps_script = self::MAPS_AUTOCOMPLETE_AND_PLACES;
        $vars = array('title' => $title, 'styles' => $styles, 'scripts' => $scripts, 'maps_script' => $maps_script);
        if (isset($error_msg)) {
            $vars['error_msg'] = $error_msg;
        }
        $this->renderTemplate('newride', true, $vars);
    }

    // Profile template and logic
    public function profile($error = null)
    {
        // Any and all POST variables are "set" (still possibly empty) if the form was submitted
        if (isset($_POST["year"])) {
            // Profile update logic
            if (!empty($_POST["year"])) $update = $this->db->query("update user set year = ? where email = ?;", "is", $_POST["year"], $this->user);
            if (!empty($_POST["major"])) $update = $this->db->query("update user set major = ? where email = ?;", "ss", $_POST["major"], $this->user);
            if (!empty($_POST["bio"])) $update = $this->db->query("update user set bio = ? where email = ?;", "ss", $_POST["bio"], $this->user);

            // Emergency contact update logic
            if (!empty($_POST["contact-phone"]) || !empty($_POST["contact-first-name"]) || !empty($_POST["contact-last-name"]) || !empty($_POST["contact-relationship"])) {
                if (!(!empty($_POST["contact-phone"]) && !empty($_POST["contact-first-name"]) && !empty($_POST["contact-last-name"]) && !empty($_POST["contact-relationship"]))) {
                    // If any of the emergency contact fields are not empty, none must be empty (because each is NOT NULL in the database)
                    $error_msg = "Please fill in all emergency contact fields.";
                } else {
                    $insert_or_update = $this->db->query("INSERT INTO user_emergency_contact (user_email, phone, first_name, last_name, relationship) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE first_name = VALUES(first_name), last_name = VALUES(last_name), relationship = VALUES(relationship)", "sssss", $this->user, $_POST["contact-phone"], $_POST["contact-first-name"], $_POST["contact-last-name"], $_POST["contact-relationship"]);
                }
            }

            // Car info update logic
            if (!empty($_POST["car-license-plate"]) || !empty($_POST["car-make"]) || !empty($_POST["car-color"])) {
                if (!(!empty($_POST["car-license-plate"]) && !empty($_POST["car-make"]) && !empty($_POST["car-color"]))) {
                    // If any of the car fields are not empty, none must be empty (because each is NOT NULL in the database)
                    $error_msg = "Please fill in all car fields.";
                } else {
                    $select = $this->db->query("select car_license_plate from driver where email = ?;", "s", $this->user);
                    if (!empty($select)) {
                        // If the user already has a car, update it
                        $license = $select[0]["car_license_plate"];
                        $update = $this->db->query("update driver_car set license_plate = ?, make = ?, color = ? where license_plate = ?;", "ssss", $_POST["car-license-plate"], $_POST["car-make"], $_POST["car-color"], $license);
                    } else {
                        // Otherwise, insert a new car and then driver
                        $insert = $this->db->query("insert into driver_car (license_plate, make, color) values (?, ?, ?);", "sss", $_POST["car-license-plate"], $_POST["car-make"], $_POST["car-color"]);
                        $insert2 = $this->db->query("insert into driver (email, car_license_plate) values (?, ?);", "ss", $this->user, $_POST["car-license-plate"]);
                    }
                }
            }

            // Rider info update logic
            if (!empty($_POST["contributions"])) $insert_or_update = $this->db->query("INSERT INTO rider (email, contributions) VALUES (?, ?) ON DUPLICATE KEY UPDATE contributions = VALUES(contributions)", "ss", $this->user, $_POST["contributions"]);
        }

        // Get all user info
        $user_info = $this->db->query("select * from user where email = ?;", "s", $this->user);

        $contact_query = $this->db->query("select * from user_emergency_contact where user_email = ?;", "s", $this->user);
        if (!empty($contact_query)) $contact_info = $contact_query;
        else $contact_info = array(array("phone" => "", "first_name" => "", "last_name" => "", "relationship" => ""));

        $car_query = $this->db->query("select * from driver_car where license_plate = (select car_license_plate from driver where email = ?);", "s", $this->user);
        if (!empty($car_query)) $car_info = $car_query;
        else $car_info = array(array("license_plate" => "", "make" => "", "color" => ""));

        $rider_query = $this->db->query("select contributions from rider where email = ?;", "s", $this->user);
        if (!empty($rider_query)) $rider_info = $rider_query;
        else $rider_info = array(array("contributions" => ""));

        // Render profile template
        $title = "My profile";
        $styles = array("main");
        $scripts = array(
            self::NOTIFS,
            '<script type="text/javascript">
                        $(document).ready(function() {
                          $("form").on("input change", () => $("#save").attr("disabled", false));
                        });
                      </script>'
        );
        $vars = array("title" => $title, "styles" => $styles, "scripts" => $scripts, "user_info" => $user_info, "contact_info" => $contact_info, "car_info" => $car_info, "rider_info" => $rider_info);
        if (isset($error_msg)) $vars["error_msg"] = $error_msg;
        else if ($error !== null) $vars["error_msg"] = $error;
        $this->renderTemplate("profile", true, $vars);
    }

    public function messages()
    {
        $messages = $this->db->query("select * from message where sender_email = ? or recipient_email = ? order by time_sent desc;", "ss", $this->user, $this->user);
        if ($messages === false) {
            $error_msg = "Error retrieving messages.";
        }

        $title = "Messages";
        $styles = array("main");
        $scripts = array(self::NOTIFS);
        $vars = array("title" => $title, "styles" => $styles, "scripts" => $scripts, "messages" => $messages);
        if (isset($error_msg)) {
            $vars["error_msg"] = $error_msg;
        }
        $this->renderTemplate("messages", true, $vars);
    }

    public function deleteAccount()
    {
        $delete = $this->db->query("delete from user where email = ?;", "s", $this->user);
        if ($delete === false) {
            $error_msg = "Error deleting user.";
            die($error_msg);
        } else {
            session_destroy();
            header("Location: /");
        }
    }

    // User ride listings template and logic
    public function myRides()
    {
        $posted = $this->db->query("select * from ride where driver_email = ?;", "s", $this->user);
        if ($posted === false) {
            $error_msg = "Error checking for posted rides.";
        }
        foreach ($posted as &$ride) {
            $seats_open = $this->calculateSeatsOpen($ride["id"]);
            $ride["seats_open"] = $seats_open;
            unset($ride);
        }

        $joined = array();
        $joined_entries = $this->db->query("select id from ride_riders where rider_email = ?;", "s", $this->user);
        if ($joined_entries === false) {
            $error_msg = "Error checking for joined rides.";
        } else if (!empty($joined_entries)) {
            foreach ($joined_entries as $entry) {
                $ride_info = $this->db->query("select * from ride where id = ?;", "i", $entry["id"]);
                $joined[] = $ride_info[0]; // weird PHP syntax for appending to array without overhead of function call
            }
            foreach ($joined as &$ride) {
                $seats_open = $this->calculateSeatsOpen($ride["id"]);
                $ride["seats_open"] = $seats_open;
                unset($ride);
            }
        }

        $title = "My rides";
        $styles = array('main');
        $scripts = array(self::NOTIFS, self::UTILS, self::GOOGLE_API);
        $maps_script = self::MAPS_PLACES;
        $vars = array('title' => $title, 'styles' => $styles, 'scripts' => $scripts, 'maps_script' => $maps_script, 'posted' => $posted, 'joined' => $joined);
        if (isset($error_msg)) {
            $vars['error_msg'] = $error_msg;
        }
        $this->renderTemplate('myrides', true, $vars);
    }

    // Delete ride listing logic
    public function deleteRide()
    {
        if (!empty($this->get_vars) and array_key_exists("id", $this->get_vars)) {
            $ride = $this->db->query("select * from ride where id = ?;", "i", $this->get_vars["id"]);
            if ($ride === false) {
                $error_msg = "Error checking for ride.";
            } else if (empty($ride)) {
                $error_msg = "No ride found with given ID.";
            } else if ($ride[0]["driver_email"] != $this->user) {
                $error_msg = "You are not authorized to delete this ride!";
            } else {
                $delete = $this->db->query("delete from ride where id = ?;", "i", $this->get_vars["id"]);
                if ($delete === false) {
                    $error_msg = "Error deleting ride.";
                } else {
                    $success_msg = "Successfully deleted ride.";
                }
            }
        }

        if (isset($error_msg)) {
            $_SESSION["error_msg"] = $error_msg;
        } else {
            $_SESSION["success_msg"] = $success_msg;
        }
        header("Location: /myrides");
    }

    // Leave ride logic
    public function leaveRide()
    {
        if (!empty($this->get_vars) and array_key_exists("id", $this->get_vars)) {
            $id = $this->get_vars["id"];

            $ride = $this->db->query("select * from ride where id = ?;", "i", $id);
            if ($ride === false) {
                $error_msg = "Error checking for ride.";
            } else if (empty($ride)) {
                $error_msg = "No ride found with given ID.";
            } else {
                $entry = $this->db->query("select * from ride_riders where id = ? and rider_email = ?;", "is", $id, $this->user);
                if ($entry === false) {
                    $error_msg = "Error checking for entry.";
                } else if (empty($entry)) {
                    $error_msg = "No entry found with given ride ID and rider email.";
                } else {
                    $delete = $this->db->query("delete from ride_riders where id = ? and rider_email = ?;", "is", $id, $this->user);

                    $success_msg = "Successfully left ride.";
                }
            }
        }

        if (isset($error_msg)) {
            $_SESSION["error_msg"] = $error_msg;
        } else {
            $_SESSION["success_msg"] = $success_msg;
        }
        header("Location: /myrides");
    }

    // Ride listing info JSON
    public function rideInfo()
    {
        if (!empty($this->get_vars) and array_key_exists("id", $this->get_vars)) {
            $id = $this->get_vars["id"];
            $ride_info = $this->db->query("select * from ride where id = ?;", "i", $id);
            if ($ride_info === false) {
                die("Error checking for ride.");
            } else if (empty($ride_info)) {
                die("No ride found with given ID.");
            }

            $driver_email = $ride_info[0]["driver_email"];
            $driver_info = $this->db->query("select first_name, last_name from user where email = ?;", "s", $driver_email);
            if ($driver_info === false) {
                die("Error checking for driver.");
            } else if (empty($driver_info)) {
                die("No driver found with given ID.");
            }

            $car_info = $this->db->query("select * from driver_car where license_plate = (select car_license_plate from driver where email = ?);", "s", $driver_email);
            if ($car_info === false) {
                die("Error checking for car.");
            } else if (empty($car_info)) {
                die("No car found with given license plate.");
            }

            $orig_addr = $ride_info[0]["orig_addr"];
            $orig_coords = $this->db->query("select latitude, longitude from coordinates where address = ?;", "s", $orig_addr);
            if ($orig_coords === false) {
                die("Error checking for origin coordinates.");
            } else if (empty($orig_coords)) {
                die("No coordinates found with given origin address.");
            }

            $dest_addr = $ride_info[0]["dest_addr"];
            $dest_coords = $this->db->query("select latitude, longitude from coordinates where address = ?;", "s", $dest_addr);
            if ($dest_coords === false) {
                die("Error checking for destination coordinates.");
            } else if (empty($dest_coords)) {
                die("No coordinates found with given destination address.");
            }

            $waypoints = $this->db->query("select address from waypoints where ride = ?;", "i", $id);
            if (!empty($waypoints)) {
                array_flip($waypoints);
                // foreach ($waypoints as $addr) {
                //   // TODO: $addr => $coords
                // }
            }

            // Make new array excluding driver email, origin address, and destination address, as we want to provide custom driver and address info
            $data = array_diff_key($ride_info[0], array_flip(array("driver_email", "orig_addr", "dest_addr")));
            $data["driver"] = array("first_name" => $driver_info[0]["first_name"], "last_name" => $driver_info[0]["last_name"], "email" => $driver_email, "car" => $car_info[0]);
            $data["origin"] = ["address" => $orig_addr, "latitude" => $orig_coords[0]["latitude"], "longitude" => $orig_coords[0]["longitude"]];
            $data["destination"] = ["address" => $dest_addr, "latitude" => $dest_coords[0]["latitude"], "longitude" => $dest_coords[0]["longitude"]];
            $data["waypoints"] = $waypoints;

            header("Content-Type: application/json");
            echo json_encode($data);
        } else {
            die("No ID given.");
        }
    }

    // Request logic
    public function request()
    {
        // Check that the user is a rider
        $rider = $this->db->query("select * from rider where email = ?;", "s", $this->user);
        if ($rider === false) {
            $error_msg = "Error checking for rider.";
        } else if (empty($rider)) {
            $error_msg = "You are not a rider! You must specify contributions to request to join a ride.";
            return $this->profile($error_msg);
        }

        // Get ride ID
        if (empty($this->get_vars) or !array_key_exists("id", $this->get_vars)) {
            die("No ID given.");
        }
        $id = $this->get_vars["id"];

        // Validate ride ID and verify that there are seats open
        $seats_open = $this->calculateSeatsOpen($id);
        if ($seats_open == -1) {
            die("No ride found with given ID.");
        } else if ($seats_open == 0) {
            die("No seats left!");
        }

        // Check that the user has not already requested to join this ride (or is already in it)
        $request = $this->db->query("select * from request where id = ? and rider_email = ?;", "is", $id, $this->user);
        if (!empty($request)) {
            die("You have already requested to join this ride!");
        }

        $entry = $this->db->query("select * from ride_riders where id = ? and rider_email = ?;", "is", $id, $this->user);
        if (!empty($entry)) {
            die("You are already in this ride!");
        }

        // If user is submitting request, insert relevant records into database; otherwise, serve request page
        if (isset($_POST["pickup"])) {
            $pickup_addr = empty($_POST["pickup-addr"]) ? null : $_POST["pickup-addr"];
            $dropoff_addr = empty($_POST["dropoff-addr"]) ? null : $_POST["dropoff-addr"];
            $insert = $this->db->query("insert into request (id, rider_email, pickup_addr, dropoff_addr) values (?, ?, ?, ?);", "isss", $id, $this->user, $pickup_addr, $dropoff_addr);
            if ($insert === false) {
                die("Error inserting request.");
            } else {
                header("Location: /rides");
            }
        } else {
            $title = "Request";
            $styles = array('main');
            $scripts = array(self::NOTIFS, self::UTILS, self::GOOGLE_API);
            $maps_script = self::MAPS_AUTOCOMPLETE_AND_PLACES;
            $vars = array('title' => $title, 'styles' => $styles, 'scripts' => $scripts, 'maps_script' => $maps_script, 'ride' => $id);
            $this->renderTemplate('request', true, $vars);
        }
    }

    // Notifications JSON
    public function getNotifs()
    {
        // Notifications
        $requests = array(); // Assoc. array of ride IDs to arrays of rider emails, e.g., [1 => ["nid3dhu@virginia.edu"], 2 => ["nid3dhu@virginia.edu", "abc1def@virginia.edu"]]
        $responses = array(); // Assoc. array of ride ID to response, e.g., [1 => 1, 2 => 2]

        // Rides posted by current user
        $user_rides = $this->db->query("select id from ride where driver_email = ?;", "s", $this->user);
        if ($user_rides === false) {
            die("Error checking for rides.");
        }

        foreach ($user_rides as $ride) {
            $db_requests = $this->db->query("select * from request where id = ?;", "i", $ride['id']);
            foreach ($db_requests as $request) {
                if (!array_key_exists($request['id'], $requests)) {
                    $requests[$request['id']] = array();
                }
                $requests[$request['id']][] = $request['rider_email'];
            }
        }

        $db_responses = $this->db->query("select * from response where rider_email = ?;", "s", $this->user);
        foreach ($db_responses as $response) {
            $responses[$response['id']] = $response['response'];
        }

        $json_obj = array("requests" => $requests, "responses" => $responses);
        header("Content-Type: application/json");
        echo json_encode($json_obj);
    }

    // Request info JSON
    public function requestInfo()
    {
        if (!empty($this->get_vars) and array_key_exists("ride", $this->get_vars) and array_key_exists("user", $this->get_vars)) {
            $data = array();

            $request = $this->db->query("select * from request where id = ? and rider_email = ?;", "is", $this->get_vars["ride"], $this->get_vars["user"]);
            if ($request === false) {
                die("Error checking for request.");
            } else if (empty($request)) {
                die("No request found with given ride ID and rider email.");
            }

            $ride = $this->db->query("select driver_email, orig_addr, dest_addr from ride where id = ?;", "i", $this->get_vars["ride"]);
            if ($ride === false) {
                die("Error checking for ride.");
            } else if (empty($ride)) {
                die("No ride found with given ID.");
            } else if ($ride[0]["driver_email"] != $this->user) {
                die("You are not authorized to view this request!");
            }
            $data["orig_addr"] = $ride[0]["orig_addr"];
            $data["dest_addr"] = $ride[0]["dest_addr"];

            $rider = $this->db->query("select email, first_name, last_name, contributions from user natural join rider where email = ?;", "s", $this->get_vars["user"]);
            if ($rider === false) {
                die("Error checking for rider.");
            } else if (empty($rider)) {
                die("No rider found with given email.");
            }
            $data["rider"] = array("first_name" => $rider[0]["first_name"], "last_name" => $rider[0]["last_name"], "email" => $rider[0]["email"], "contributions" => $rider[0]["contributions"]);

            header("Content-Type: application/json");
            echo json_encode($data);
        } else {
            die("Ride or user missing from query.");
        }
    }

    // Respond logic
    public function respond()
    {
        if (empty($this->get_vars) or !array_key_exists("ride", $this->get_vars) or !array_key_exists("user", $this->get_vars) or !array_key_exists("response", $this->get_vars) or !($this->get_vars["response"] == 0 or $this->get_vars["response"] == 1)) {
            die("Ride ID, rider email, or response missing from query, or response is invalid.");
        }

        $id = $this->get_vars["ride"];
        $rider = $this->get_vars["user"];
        $response = $this->get_vars["response"];

        // Validate request
        $request = $this->db->query("select * from request where id = ? and rider_email = ?;", "is", $id, $rider);
        if ($request === false) {
            die("Error checking for request.");
        } else if (empty($request)) {
            die("No request found with given ride ID and rider email.");
        }

        // Validate ride
        $ride = $this->db->query("select driver_email from ride where id = ?;", "i", $id);
        if ($ride === false) {
            die("Error checking for ride.");
        } else if (empty($ride)) {
            die("No ride found with given ID.");
        } else if ($ride[0]["driver_email"] != $this->user) {
            die("You are not permitted to respond to this request!");
        }

        $insert = $this->db->query("insert into response (id, rider_email, response) values (?, ?, ?);", "isi", $id, $rider, $response);
        if ($insert === false) {
            die("Error inserting response.");
        }

        header("Location: /rides");
    }

    // Mark response notifications as read (delete them from database)
    public function deleteResponse()
    {
        if (!empty($this->get_vars) and array_key_exists("ride", $this->get_vars)) {
            $id = $this->get_vars["ride"];

            $response = $this->db->query("select * from response where id = ? and rider_email = ?;", "is", $id, $this->user);
            if ($response === false) {
                die("Error checking for response.");
            } else if (empty($response)) {
                die("No response found with given ride ID and rider email.");
            }

            $delete = $this->db->query("delete from response where id = ? and rider_email = ?;", "is", $id, $this->user);
            if ($delete === false) {
                die("Error deleting request.");
            }

            header("Location: /rides");
        } else {
            die("Ride ID missing from query.");
        }
    }

    public function report()
    {
        if (isset($_POST["reportee-email"])) {
            $reportee_email = $_POST["reportee-email"];
            $reportee = $this->db->query("select * from user where email = ?;", "s", $reportee_email);
            if ($reportee === false) {
                $error_msg = "Error checking for reportee.";
            } else if (empty($reportee)) {
                $error_msg = "No user found with given email.";
            } else {
                $insert = $this->db->query("insert into report (reporter_email, reportee_email, reason, info) values (?, ?, ?, ?);", "ssss", $this->user, $reportee_email, $_POST["reason"], $_POST["info"]);
                if ($insert === false) {
                    $error_msg = "Error inserting report.";
                } else {
                    header("Location: /rides");
                }
            }
        }

        $title = "Report user";
        $styles = array("main");
        $scripts = array(self::NOTIFS);
        $vars = array("title" => $title, "styles" => $styles, "scripts" => $scripts);
        if (isset($error_msg)) {
            $vars["error_msg"] = $error_msg;
        }
        $this->renderTemplate("report", true, $vars);
    }
}
