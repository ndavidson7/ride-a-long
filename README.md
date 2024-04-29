# :car: **Ride-A-Long**

Ride sharing web app designed by and for college students. Carpool with your classmates!

## :white_check_mark: TODO

-   Email verification
    -   Prod mailer
-   Add chat:
    -   Use Laravel Echo Presence Channels to track each user's online status (https://laravel.com/docs/11.x/broadcasting#presence-channels)
    -   Consider switching to Livewire component
-   Attractive landing page
-   Sign in with phone number
-   Login with Google to verify student status (https://laravel.com/docs/10.x/socialite)
-   Convert to TALL stack
    -   Overhaul styling. Pages left:
        -   requests.\*
        -   alerts.\*
        -   profiles.\*
        -   settings.\*
        -   landing
-   Allow users to create alerts to be notified when:
    -   Seat becomes available in previously full ride
    -   New ride is created matching desired criteria
-   Overhaul forms
    -   Check validation and add error feedback div to every (required?) input
    -   Refactor forms JS?
-   Make use of form requests, policies, and static factory methods for increased validation and security and reduced controller method complexity
-   Notifications via email and maybe SMS (https://laravel.com/docs/10.x/notifications & https://laravel.com/docs/10.x/events)
-   Change all timezone-related code to use user's local timezone while ensuring DB records use UTC (dayjs on front end for converting to user's system's timezone)
-   Allow users to upload multiple cars, and decouple it from profile edit
-   Pricing model
-   Distinguish between ride types based on origin/destination: airports, cities, schools, etc.
-   Soft deletes
    -   old rides (DELETE FROM rides WHERE start_time < NOW();)
    -   requests (don't allow users to request the same ride again after soft delete)
-   Move address geocoding to backend in order to reuse cached coordinates, as well as re-fetch coordinates for old addresses that are still being referenced (see below)
-   Delete user's outstanding requests when request with conflicting time is accepted (or maybe allow user's to set a priority with their requests)
-   Show password toggle
-   Infinite scroll pagination
-   Cache Mapbox Directions API results in sessionStorage?
-   Rides index on large map, show routes when filter applied
-   Database concurrency (https://blog.tobexkee.com/handling-concurrency-attacks-in-laravel)

## Production Checklist

-   Cache (icons, config, routes, etc.)

## :hammer_and_wrench: Tech Stack

Primary built using the TALL stack:

-   [TailwindCSS](https://tailwindcss.com)
-   [AlpineJS](https://alpinejs.dev)
-   [Laravel](https://laravel.com/)
-   [Livewire](https://livewire.laravel.com/)

Additional technologies:

-   MySQL
-   Vite
-   Pusher

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

    `php artisan serve`, `npm run dev`, and `php artisan queue:listen`

The application should now be reachable at [http://localhost:8000]([http://localhost:8000]).

There are a number of randomly generated users in the `users` database table. All use the highly secure password "password". Two users will always exist: "nid3dhu@virginia.edu" and "ab1cd@virginia.edu". Use these to test the application, or make your own user, rides, requests, etc.
