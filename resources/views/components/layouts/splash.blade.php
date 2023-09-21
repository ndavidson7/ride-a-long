<!DOCTYPE html>
<html lang="en" class="h-100">

<x-head :$title :$entries />

<body class="splash">

    <x-headers.splash />

    {{ $slot }}

    <x-footer />

    @if (session()->has('status'))
        <x-flash-message :type="session('status')" :message="session('message')" />
    @endif

</body>

</html>
