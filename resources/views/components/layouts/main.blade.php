<!DOCTYPE html>
<html lang="en" class="h-100">

<x-head :title="$title" />

<body class="d-flex flex-column h-100">

    <x-headers.main />

    {{ $slot }}

    <x-footer />

    @if (session()->has('success'))
        <div>
            <p>{{ session('success') }}</p>
        </div>
    @endif

</body>

</html>
