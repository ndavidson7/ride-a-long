# :car: **Ride-A-Long**

Ride sharing web app designed by and for college students. Carpool with your classmates!

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

### Install dependencies

1. Install [composer](https://getcomposer.org/) if you have not already.
2. Install [Node.js and npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm) if you have not already.
3. Open the repository in your terminal and run:

    ```
    composer install
    npm install
    ```

### Configure

1. Create a new database.
2. Copy `/.env.example` to `/.env` and modify it to use your new database's connection parameters.
3. Run `php artisan migrate:fresh --seed`.

### Host

-   Optionally, install [XAMPP](https://www.apachefriends.org/) to host Ride-A-Long locally.

    -   Configure XAMPP's httpd.conf to serve files from this repository's `/public` folder however you prefer.

-   If you do not wish to use XAMPP, you can use Artisan's built-in development server with `php artisan serve`, but you will need some means of hosting a MySQL database.
