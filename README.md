# :car: **Ride-A-Long**

Ride sharing web app designed by and for college students. Carpool with your classmates!

## :white_check_mark: TODO

-   [x] Rewrite in Laravel
-   [x] Check if user clicked a different ride info card before re-fetching and rendering the same route and instead simply display the modal again
-   [x] Allow riders to request pickup/dropoff spots
-   [ ] Email verification
    -   [x] Local mailer
    -   [ ] Prod mailer
-   [x] Route for leave ride button
-   [x] Search/filter on navbar
-   [x] Pagination on rides.index
-   [x] Preview map hidden until updated
-   [x] Profile picture upload
-   [x] Make PFP upload async
-   [x] Display button relevant to user on ride modal
-   [x] Only display city (and maybe state) on rides index cards. Show more detail inside modal
-   [x] Make formatted_address in ride info modal an anchor to the user agent's default GPS app
-   [x] My rides page (sort of hacked... but works well enough)
-   [x] Broadcast new notifications (https://laravel.com/docs/10.x/broadcasting)
-   [ ] Add messaging functionality
    -   [x] Ride chat
    -   [ ] Direct messages
-   [ ] Notifications via email and maybe SMS (https://laravel.com/docs/10.x/notifications & https://laravel.com/docs/10.x/events)
-   [x] Only show driver's car to passengers
-   [x] Import majors
-   [x] Overhaul forms
    -   [x] Check validation and add error feedback (https://getbootstrap.com/docs/5.3/forms/validation/)
-   [x] Attractive landing page
-   [ ] Overhaul styling
-   [ ] Forgot password route
-   [ ] text-muted deprecated, text-secondary is not a substitute (https://getbootstrap.com/docs/5.3/utilities/colors/#colors)
-   [ ] Allow users to upload multiple cars, and decouple it from profile edit
-   [ ] Soft delete old rides (DELETE FROM rides WHERE start_time < NOW();)
-   [ ] Move address geocoding to backend in order to reuse cached coordinates, as well as re-fetch coordinates for old addresses that are still being referenced (see below)
-   [ ] Delete from address table after 30 days of record creation in accordance with Google Maps ToS (https://cloud.google.com/maps-platform/terms/maps-service-terms)
    -   [x] Create scheduled Artisan command
    -   [ ] Add cron entry to server (https://laravel.com/docs/10.x/scheduling#running-the-scheduler)
-   [ ] Change all timezone-related code to use user's local timezone (while ensuring DB records use UTC)
    -   [ ] Request user's location OR allow user to set their location/timezone
-   [ ] Use dividers in messages?
-   [ ] Refactor view-specific JS to be more modular, reusable
-   [ ] Delete user's outstanding requests when request with conflicting time is accepted (or maybe allow user's to set a priority with their requests)
-   [ ] Allow users to create alerts:
    -   [ ] When seat becomes available in previously full ride
    -   [ ] New ride is created matching desired route
-   [ ] Infinite scroll pagination
-   [ ] Cache Google DirectionsResults in sessionStorage (gray area of Google ToS...)
-   [ ] Rides index on large map, show routes when filter applied
-   [ ] Pricing model
-   [ ] Database concurrency (https://blog.tobexkee.com/handling-concurrency-attacks-in-laravel)

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
1. Copy and paste the API key into the `MAPS_JS_API_KEY` variable in `.env`.

### Host

If you followed the above instructions exactly, you can either host Ride-A-Long using XAMPP or Artisan's built-in development server. I would suggest Artisan, as it _just works_ (:crossed_fingers:).

1. Start your MySQL server.
1. In separate terminals, run:

    `php artisan serve`

    and

    `npm run dev`

The application should now be reachable at [http://localhost:8000]([http://localhost:8000]).

There are a number of randomly generated users in the `users` database table. All use the highly secure password _password_. My user, _nid3dhu@virginia.edu_, owns a ride and the second user, _ab1cd@virginia.edu_, has requested to join it. Use these to test the application, or make your own user, rides, requests, etc.
