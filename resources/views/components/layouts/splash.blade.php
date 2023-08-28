<!DOCTYPE html>
<html lang="en" class="h-100">

<x-head :title="$title" :entries="$entries" />

<body class="splash">

    <x-headers.splash />

    {{ $slot }}

    <x-footer />

</body>

</html>
