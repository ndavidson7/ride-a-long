<!DOCTYPE html>
<html lang="en" class="h-100">

<x-head :$title :$entries />

<body class="d-flex flex-column h-100">

    <x-headers.navbar />

    {{ $slot }}

    {{-- <x-footer /> --}}

    @if (session()->has('status'))
        <x-flash-message :type="session('status')" :message="session('message')" />
    @endif

</body>

</html>
