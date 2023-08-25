<!DOCTYPE html>
<html lang="en" class="h-100">

<x-head :title="$title" />

<body class="d-flex flex-column align-items-center text-center h-100 splash">

    <x-headers.splash />

    {{ $slot }}

    <x-footer />

</body>

</html>
