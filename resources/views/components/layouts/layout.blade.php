<!DOCTYPE html>
<html lang="en">

<x-head :$title :$entries />

<body {{ $attributes }}>

    @if ($header)
        <x-dynamic-component :component="$header" />
    @endif

    <main>
        {{ $slot }}
    </main>

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
