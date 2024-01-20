<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body {{ $attributes->class(['grid', 'md:grid-cols-2']) }}>

    <x-headers.splash />

    <main>
        {{ $slot }}
    </main>

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
