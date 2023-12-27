<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body class="splash d-flex flex-column min-vh-100">

    <x-headers.splash />

    {{ $slot }}

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
