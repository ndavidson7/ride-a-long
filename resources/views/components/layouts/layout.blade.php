<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body class="{{ $bodyClasses }} d-flex flex-column min-vh-100">

    <x-dynamic-component :component="$header" />

    {{ $slot }}

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
