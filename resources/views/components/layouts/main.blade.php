<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Nicholas Davidson">
    <meta name="description" content="Ride-A-Long: Carpool with your classmates! {{ $title }}">
    <meta name="keywords" content="Ride-a-long, ridealong, ride, uva, carpool, hoosdriving, hoosriding">
    <title>Ride-A-Long - {{ $title }}</title>
    <noscript>JavaScript must be enabled to use Ride-A-Long.</noscript>
    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    {{-- FONT AWESOME --}}
    <script defer src="https://kit.fontawesome.com/d21f3fd807.js" crossorigin="anonymous"></script>
    {{-- all styles and scripts? --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<x-header :with-navbar="$withNavbar" />

{{ $slot }}

<x-footer />

</html>
