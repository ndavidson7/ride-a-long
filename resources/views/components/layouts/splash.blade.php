<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body {{ $attributes->class(['grid', 'md:grid-cols-2']) }}>

    <x-headers.splash />

    <main class="flex min-h-full flex-col flex-wrap items-center justify-center py-10">
        {{ $slot }}
    </main>

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
