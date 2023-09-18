# :car: **Ride-A-Long**

Ride sharing web app designed by and for college students. Carpool with your classmates!

## :white_check_mark: TODO

-   [ ] Rewrite in Laravel
-   [x] Check if user clicked a different ride info card before re-fetching and rendering the same route and instead simply display the modal again
-   [ ] Allow riders to request pickup/dropoff spots:
    -   [x] Update request notifications to show the rider's requested pickup and/or dropoff if either exists (maybe reuse map modal)
    -   [ ] If a rider requests a pickup and/or dropoff spot and the driver accepts:
        1. add a waypoint with a unique ID and an order of -1 for each spot
        2. save the ID(s) to the new ride_riders record (so we have a way to know who requested each spot)
        3. when the ride info is requested, if there are any waypoints with an order of -1, optimize the waypoints and update their order accordingly; otherwise, order by order and display without optimizations
-   [ ] Search/filter on navbar
-   [ ] Event/PubSub system for notifications? As opposed to fetching all on each page load
-   [x] Don't display request button on ride info modal for ride driver
-   [ ] Only display city (and maybe state) on rides index cards. Show more detail inside modal
-   [ ] Make formatted_address in ride info modal an anchor to the user agent's default GPS app
-   [ ] Add messaging functionality
-   [ ] Delete from address table after 30 days of record creation in accordance with Google Maps ToS (https://cloud.google.com/maps-platform/terms/maps-service-terms)
-   [ ] Cache recently fetched ride data?
-   [ ] Update footer ("Made with <3 by a former Hoo" & visible on all pages)

## :hammer_and_wrench: Built With

-   Laravel/PHP
-   ES6 JavaScript
-   MySQL

## Setup development environment

### Install dependencies

1. Install PHP, Apache, and MySQL. [XAMPP](https://www.apachefriends.org/) is the easiest cross-platform solution that bundles all of these together.
1. Install [composer](https://getcomposer.org/).
1. Install [Node.js and npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm).
1. Open the repository in your terminal and run:

    ```
    composer install
    npm install
    ```

### Configure

1. Run your MySQL RDBMS (in XAMPP if you chose to use it).
1. Create a new database (http://localhost/phpmyadmin if you are using XAMPP).
1. Copy `.env.example` to `.env` and modify it to use your new database's connection parameters.
1. Run `php artisan migrate:fresh --seed`.
1. Create a new [Google Cloud Project](https://console.cloud.google.com/).
1. Within the project, [enable the following APIs](https://console.cloud.google.com/apis/dashboard):
    - Directions
    - Geocoding
    - Maps JavaScript
    - Places
1. [Create a new API key](https://console.cloud.google.com/apis/credentials) with the following parameters:
    - Application restriction: Websites
    - Website restrictions: "localhost/\*"
    - API restrictions: The APIs enabled in the above step
1. Copy and paste the API key into the `MAPS_API_KEY` variable in `.env`.

### Host

If you followed the above instructions exactly, you can either host Ride-A-Long using XAMPP or Artisan's built-in development server. I would suggest Artisan, as it _just works_ (:crossed_fingers:).

1. Start your MySQL server.
1. In separate terminals, run:

    `php artisan serve`

    and

    `npm run dev`

The application should now be reachable at [http://localhost:8000]([http://localhost:8000]).

There are a number of randomly generated users in the `users` database table. All use the highly secure password _password_. My user, _nid3dhu@virginia.edu_, owns a ride and the second user, _ab1cd@virginia.edu_, has requested to join it. Use these to test the application, or make your own user, rides, requests, etc.
