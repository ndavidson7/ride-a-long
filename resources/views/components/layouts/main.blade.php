<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body class="d-flex flex-column min-vh-100">

    <x-headers.navbar />

    {{ $slot }}

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
