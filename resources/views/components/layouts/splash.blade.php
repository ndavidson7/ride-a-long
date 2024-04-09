@props(['title'])

<!DOCTYPE html>
<html lang="en">

<x-head :$title />

<body {{ $attributes->class(['grid', 'md:grid-cols-2']) }}>

    <x-headers.splash />

    <main class="flex min-h-full flex-col flex-wrap items-center justify-center px-3 py-10 md:px-6">
        {{ $slot }}
    </main>

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
