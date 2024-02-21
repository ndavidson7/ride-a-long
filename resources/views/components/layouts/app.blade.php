<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body>

    <x-headers.navbar />

    <main {{ $attributes->class(['p-5']) }}>
        {{ $slot }}
    </main>

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
