# :car: **Ride-A-Long**

Ride sharing web app designed by and for college students. Carpool with your classmates!

## :white_check_mark: TODO

-   [ ] Rewrite in Laravel
-   [ ] Allow riders to request pickup/dropoff spots:
    -   [ ] Preview button not being disabled when both checkboxes checked and one autocomplete filled in (Array.from(checkboxes)?)
    -   [ ] Update request notifications to show the rider's requested pickup and/or dropoff if either exists (maybe reuse map modal)
    -   [ ] If a rider requests a pickup and/or dropoff spot and the driver accepts:
        1. add a waypoint with a unique ID and an order of -1 for each spot
        2. save the ID(s) to the new ride_riders record (so we have a way to know who requested each spot)
        3. when the ride info is requested, if there are any waypoints with an order of -1, optimize the waypoints and update their order accordingly; otherwise, order by order and display without optimizations
-   [ ] Update .htaccess (https://stackoverflow.com/questions/23635746/htaccess-redirect-from-site-root-to-public-folder-hiding-public-in-url and https://stackoverflow.com/questions/704102/how-does-rewritebase-work-in-htaccess)
-   [ ] Add messaging functionality
-   [ ] \(Optional) Delete from coordinates table. Partition pruning might be one solution? https://stackoverflow.com/questions/9472167/what-is-the-best-way-to-delete-old-rows-from-mysql-on-a-rolling-basis

## :hammer_and_wrench: Built With

-   Laravel/PHP
-   ES6 JavaScript
-   MySQL

## :camera: Preview

### Sign in

![Sign in page](https://github.com/ndavidson7/ride-a-long/blob/main/images/signin.png?raw=true)

### Ride listings

![Rides page](https://github.com/ndavidson7/ride-a-long/blob/main/images/rides.png?raw=true)

### Ride info

![Ride info modal](https://github.com/ndavidson7/ride-a-long/blob/main/images/rideinfo.png?raw=true)

### New ride

![New ride page](https://github.com/ndavidson7/ride-a-long/blob/main/images/newride.png?raw=true)

## Setup development environment

### Installing dependencies

1. Install [composer](https://getcomposer.org/) if you have not already.
2. Install [Node.js and npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm) if you have not already.
3. Open the repository in your terminal and run:

    ```
    composer install
    npm install
    ```

### Configuration

1. Create a new database.
2. Copy `/.env.example` to `/.env` and modify it to use your new database's connection parameters.
3. Run `php artisan migrate:fresh --seed`.

### Host

-   Optionally, install [XAMPP](https://www.apachefriends.org/) to host Ride-A-Long locally.

    -   Configure XAMPP's httpd.conf to serve files from this repository's `/public` folder however you prefer.

-   If you do not wish to use XAMPP, you can use Artisan's built-in development server with `php artisan serve`, but you will need some means of hosting a MySQL database.
