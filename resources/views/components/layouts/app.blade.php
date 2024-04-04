<!DOCTYPE html>
<html lang="en">

<x-head :$title />

<body
    class="from-black/[0.025] from-[1px] to-[1px] bg-fixed [background-image:_linear-gradient(to_right,_var(--tw-gradient-stops)),_linear-gradient(to_bottom,_var(--tw-gradient-stops))] [background-size:_50px_50px]">

    <x-headers.navbar />

    <main {{ $attributes->class(['p-5']) }}>
        {{ $slot }}
    </main>

    {{-- <x-footer /> --}}

    <x-toasts.container />

</body>

</html>
