<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body>

    <x-headers.navbar />

    <main {{ $attributes }}>
        {{ $slot }}
    </main>

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
