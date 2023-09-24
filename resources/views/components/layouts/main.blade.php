<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body class="d-flex flex-column min-vh-100">

    <x-headers.navbar />

    {{ $slot }}

    {{-- <x-footer /> --}}

    @if (session()->has('status'))
        <x-flash-message :type="session('status')" :message="session('message')" />
    @endif

</body>

</html>
