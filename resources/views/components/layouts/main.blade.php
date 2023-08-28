<!DOCTYPE html>
<html lang="en" class="h-100">

<x-head :title="$title" :entries="$entries" />

<body class="d-flex flex-column h-100">

    <x-headers.navbar />

    {{ $slot }}

    <x-footer />

    @if (session()->has('status'))
        <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert"
            style="position: absolute; bottom: 20px; right: 20px; margin-bottom: 0">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('status') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

</body>

</html>
