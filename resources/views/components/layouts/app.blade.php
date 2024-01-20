<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body {{ $attributes }}>

    <x-headers.navbar />

    <main>
        {{ $slot }}
    </main>

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
