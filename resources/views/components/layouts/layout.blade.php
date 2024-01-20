<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body {{ $attributes }}>

    <x-dynamic-component :component="$header" />

    <main>
        {{ $slot }}
    </main>

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
