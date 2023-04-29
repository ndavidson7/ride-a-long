<?php
class Controller {
  private $command;
  private $db;
  private $get_vars;

  public function __construct($command, $get_vars) {
    $this->command = $command;
    $this->db = new Database();
    $this->get_vars = $get_vars;
  }

  public function run() {
    switch($this->command) {
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
  private function destroySession() {
    session_destroy();
  }


  // Sign in template and logic
  public function signIn() {
    if (isset($_POST["email"])) {
      $data = $this->db->query("select * from project_user where email = ?;", "s", $_POST["email"]);
      if ($data === false) {
        $error_msg = "Error checking for user.";
      } else if (!empty($data)) {
        if (password_verify($_POST["password"], $data[0]["password"])) {
          $_SESSION["id"] = $data[0]["id"];
          header("Location: /rides");
        } else {
          $error_msg = "Wrong password.";
        }
      } else { // empty, no user found
        $error_msg = "No user found with given email.";
      }
    }

    include "templates/signin.php";
  }

  // Sign up form template and logic
  public function signUp() {
    // Validate inputs
    // No input can be empty due to the required attribute on their HTML inputs
    // Emails are validated by their HTML pattern attribute (TODO: sign up/in through Google/Netbadge)
    if (isset($_POST["email"])) {
      if ($_POST["password"] != $_POST["password2"]) {
        $error_msg = "Passwords do not match!";
      } else if (!empty($this->db->query("select * from project_user where email = ?;", "s", $_POST["email"]))) {
        $error_msg = "Email already in use! Try signing in instead.";
      } else {
        $insert = $this->db->query("insert into project_user (first_name, last_name, email, password) values (?, ?, ?, ?);", "ssss", $_POST["first_name"], $_POST["last_name"], $_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT));
        if ($insert === false) {
          $error_msg = "Error inserting user.";
        } else {
          $id = $this->db->query("select id from project_user where email = ?;", "s", $_POST["email"]);
          if ($data === false) {
            $error_msg = "Error checking for user.";
          } else {
            $_SESSION["id"] = $id[0]["id"];
            header("Location: /profile");
          }
        }
      }
    }

    include "templates/signup.php";
  }

  // Ride listings template and logic
  public function allRides() {
    $rides = $this->db->query("select * from project_ride;");
    if ($rides === false) {
      $error_msg = "Error checking for rides.";
    }

    if (!empty($this->get_vars) and array_key_exists("output", $this->get_vars) and $this->get_vars["output"] == "json") {
      header("Content-Type: application/json");
      echo json_encode($rides);
    } else {
      include "templates/rides.php";
    }
  }

  // New ride form template and logic
  public function newRide() {
    if (isset($_POST["date"])) {
      $insert = $this->db->query("insert into project_ride (user, date, time, orig_addr, orig_lat, orig_long, dest_addr, dest_lat, dest_long, seats_total, seats_open, info) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", "isssddsddiis", $_SESSION["id"], $_POST["date"], $_POST["time"], $_POST["orig-addr"], $_POST["orig-lat"], $_POST["orig-long"],  $_POST["dest-addr"], $_POST["dest-lat"], $_POST["dest-long"], $_POST["seats"], $_POST["seats"], $_POST["info"]);
      if ($insert === false) {
        $error_msg = "Error inserting ride.";
      } else {
        header("Location: /rides");
      }
    }

    include "templates/newride.php";
  }

  // Profile template and logic
  public function profile() {
    if (isset($_POST["year"])) {
      $update = $this->db->query("update project_user set year = ? where id = ?;", "si", $_POST["year"], $_SESSION["id"]);
    }
    if (isset($_POST["studying"])) {
      $update = $this->db->query("update project_user set studying = ? where id = ?;", "si", $_POST["studying"], $_SESSION["id"]);
    }
    if (isset($_POST["bio"])) {
      $update = $this->db->query("update project_user set bio = ? where id = ?;", "si", $_POST["bio"], $_SESSION["id"]);
    }
    $user_info = $this->db->query("select * from project_user where id = ?;", "i", $_SESSION["id"]);

    include "templates/profile.php";
  }

  // User ride listings template and logic
  public function myRides() {
    $posted = $this->db->query("select * from project_ride where user = ?;", "i", $_SESSION["id"]);
    if ($posted === false) {
      $error_msg = "Error checking for posted rides.";
    }

    $joined = array();
    $joined_entries = $this->db->query("select ride from project_riders where user = ?;", "i", $_SESSION["id"]);
    if ($joined_entries === false) {
      $error_msg = "Error checking for joined rides.";
    } else if(!empty($joined_entries)) {
      foreach ($joined_entries as $entry) {
        $ride_info = $this->db->query("select * from project_ride where id = ?;", "i", $entry["ride"]);
        $joined[] = $ride_info[0];
      }
    }

    include "templates/myrides.php";
  }

  // Delete ride listing logic
  public function deleteRide() {
    if (!empty($this->get_vars) and array_key_exists("id", $this->get_vars)) {
      $ride = $this->db->query("select * from project_ride where id = ?;", "i", $this->get_vars["id"]);
      if ($ride === false) {
        $error_msg = "Error checking for ride.";
      } else if (empty($ride)) {
        $error_msg = "No ride found with given ID.";
      } else if ($ride[0]["user"] != $_SESSION["id"]) {
        $error_msg = "You are not authorized to delete this ride!";
      } else {
        $delete = $this->db->query("delete from project_ride where id = ?;", "i", $this->get_vars["id"]);
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
  public function leaveRide() {
    if (!empty($this->get_vars) and array_key_exists("id", $this->get_vars)) {
      $ride_id = $this->get_vars["id"];

      $ride = $this->db->query("select * from project_ride where id = ?;", "i", $ride_id);
      if ($ride === false) {
        $error_msg = "Error checking for ride.";
      } else if (empty($ride)) {
        $error_msg = "No ride found with given ID.";
      } else {
        $entry = $this->db->query("select * from project_riders where ride = ? and user = ?;", "ii", $ride_id, $_SESSION["id"]);
        if ($entry === false) {
          $error_msg = "Error checking for entry.";
        } else if (empty($entry)) {
          $error_msg = "No entry found with given ride and user IDs.";
        } else {
          $update = $this->db->query("update project_ride set seats_open = ? where id = ?;", "ii", $ride[0]["seats_open"]+1, $ride_id);

          $delete = $this->db->query("delete from project_riders where ride = ? and user = ?;", "ii", $ride_id, $_SESSION["id"]);

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
  public function rideInfo() {
    if (!empty($this->get_vars) and array_key_exists("id", $this->get_vars)) {
      $data = array();

      $id = $this->get_vars["id"];
      $ride_info = $this->db->query("select user, date, time, orig_addr, orig_lat, orig_long, dest_addr, dest_lat, dest_long, seats_total, seats_open, info from project_ride where id = ?;", "i", $id);
      if ($ride_info === false) {
        die("Error checking for ride.");
      } else if (empty($ride_info)) {
        die("No ride found with given ID.");
      }

      $driver_id = $ride_info[0]["user"];
      $driver_info = $this->db->query("select first_name, last_name, email from project_user where id = ?;", "i", $driver_id);
      if ($driver_info === false) {
        die("Error checking for driver.");
      } else if (empty($driver_info)) {
        die("No driver found with given ID.");
      }

      $ride_info[0]["user"] = array("first_name"=>$driver_info[0]["first_name"], "last_name"=>$driver_info[0]["last_name"], "email"=>$driver_info[0]["email"]);
      header("Content-Type: application/json");
      echo json_encode($ride_info[0]);
    } else {
      die("No ID given.");
    }
  }

  // Request logic
  public function request() {
    if (!empty($this->get_vars) and array_key_exists("id", $this->get_vars)) {
      $id = $this->get_vars["id"];
      // Validate ride ID
      $ride_info = $this->db->query("select seats_open from project_ride where id = ?;", "i", $id);
      if ($ride_info === false) {
        die("Error checking for ride.");
      } else if (empty($ride_info)) {
        die("No ride found with given ID.");
      } else if ($ride_info[0]['seats_open'] == 0) {
        die("No seats left!");
      }

      if (isset($_POST["message"])) {
        $insert = $this->db->query("insert into project_requests (ride, user) values (?, ?);", "ii", $id, $_SESSION["id"]);
        if ($insert === false) {
          die("Error inserting ride.");
        } else {
          header("Location: /rides");
        }
      } else {
        include "templates/request.php";
      }
    }
  }

  // Notifications JSON
  public function getNotifs() {
    // Notifications
    $requests = array(); // Assoc. array of ride IDs to arrays of user IDs, e.g., [1 => [1, 2, 3], 2 => [2, 3], 3 => [5]]
    $responses = array(); // Assoc. array of ride ID to response, e.g., [1 => "accept", 2 => "deny"]

    // Rides posted by current user
    $user_rides = $this->db->query("select id from project_ride where user = ?;", "i", $_SESSION["id"]);
    if ($user_rides === false) {
      die("Error checking for rides.");
    }

    foreach ($user_rides as $ride) {
      $db_requests = $this->db->query("select * from project_requests where ride = ?;", "i", $ride['id']);
      foreach ($db_requests as $request) {
        if (!array_key_exists($request['ride'], $requests)) {
          $requests[$request['ride']] = array();
        }
        $requests[$request['ride']][] = $request['user'];
      }
    }

    $db_responses = $this->db->query("select * from project_responses where user = ?;", "i", $_SESSION["id"]);
    foreach ($db_responses as $response) {
      $responses[$response['ride']] = $response['response'];
    }

    $json_obj = array("requests"=>$requests, "responses"=>$responses);
    header("Content-Type: application/json");
    echo json_encode($json_obj);
  }

  // Request info JSON
  public function requestInfo() {
    if (!empty($this->get_vars) and array_key_exists("ride", $this->get_vars) and array_key_exists("user", $this->get_vars)) {
      $data = array();

      $request = $this->db->query("select * from project_requests where ride = ? and user = ?;", "ii", $this->get_vars["ride"], $this->get_vars["user"]);
      if ($request === false) {
        die("Error checking for request.");
      } else if (empty($request)) {
        die("No request found with given ride and user IDs.");
      }

      $ride = $this->db->query("select user, orig_addr, dest_addr from project_ride where id = ?;", "i", $this->get_vars["ride"]);
      if ($ride === false) {
        die("Error checking for ride.");
      } else if (empty($ride)) {
        die("No ride found with given ID.");
      } else if ($ride[0]['user'] != $_SESSION["id"]) {
        die("You are not permitted to see this request!");
      }
      $data["orig_addr"] = $ride[0]["orig_addr"];
      $data["dest_addr"] = $ride[0]["dest_addr"];

      $user = $this->db->query("select first_name, last_name, email from project_user where id = ?;", "i", $this->get_vars["user"]);
      if ($user === false) {
        die("Error checking for user.");
      } else if (empty($user)) {
        die("No user found with given ID.");
      }
      $data["user"] = array("first_name"=>$user[0]["first_name"], "last_name"=>$user[0]["last_name"], "email"=>$user[0]["email"]);

      header("Content-Type: application/json");
      echo json_encode($data);
    } else {
      die("Ride or user missing from query.");
    }
  }

  // Respond logic
  public function respond() {
    if (!empty($this->get_vars) and array_key_exists("ride", $this->get_vars) and array_key_exists("user", $this->get_vars) and array_key_exists("response", $this->get_vars) and ($this->get_vars["response"] == "deny" or $this->get_vars["response"] == "accept")) {
      $ride_id = $this->get_vars["ride"];
      $user_id = $this->get_vars["user"];
      $response = $this->get_vars["response"];

      $request = $this->db->query("select * from project_requests where ride = ? and user = ?;", "ii", $ride_id, $user_id);
      if ($request === false) {
        die("Error checking for request.");
      } else if (empty($request)) {
        die("No request found with given ride and user IDs.");
      }

      $ride = $this->db->query("select user, seats_open from project_ride where id = ?;", "i", $ride_id);
      if ($ride === false) {
        die("Error checking for ride.");
      } else if (empty($ride)) {
        die("No ride found with given ID.");
      } else if ($ride[0]['user'] != $_SESSION["id"]) {
        die("You are not permitted to respond to this request!");
      }

      $delete = $this->db->query("delete from project_requests where ride = ? and user = ?;", "ii", $ride_id, $user_id);
      if ($delete === false) {
        die("Error deleting request.");
      }
      $insert = $this->db->query("insert into project_responses (ride, user, response) values (?, ?, ?);", "iis", $ride_id, $user_id, $response);
      if ($insert === false) {
        die("Error inserting response.");
      }
      if ($response == "accept") {
        $update = $this->db->query("update project_ride set seats_open = ? where id = ?;", "ii", $ride[0]['seats_open']-1, $ride_id);

        $insert2 = $this->db->query("insert into project_riders (ride, user) values (?, ?);", "ii", $ride_id, $user_id);
        if ($insert2 === false) {
          die("Error inserting rider.");
        }
      }
      header("Location: /rides");
    } else {
      die("Ride, user, or response missing from query, or response is invalid.");
    }
  }

  // Mark response notifications as read (delete them from database)
  public function deleteResponse() {
    if (!empty($this->get_vars) and array_key_exists("ride", $this->get_vars)) {
      $ride_id = $this->get_vars["ride"];

      $response = $this->db->query("select * from project_responses where ride = ? and user = ?;", "ii", $ride_id, $_SESSION["id"]);
      if ($response === false) {
        die("Error checking for response.");
      } else if (empty($response)) {
        die("No response found with given ride and user IDs.");
      }

      $delete = $this->db->query("delete from project_responses where ride = ? and user = ?;", "ii", $ride_id, $_SESSION["id"]);
      if ($delete === false) {
        die("Error deleting request.");
      }

      header("Location: /rides");
    } else {
      die("Ride missing from query.");
    }
  }
}
