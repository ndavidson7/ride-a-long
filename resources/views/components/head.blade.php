@props(['title'])

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Nicholas Davidson">
    <meta name="description" content="{{ config('app.name') }}: Carpooling made easy. {{ $title }} page.">
    <meta name="keywords" content="{{ config('app.name') }}, carpool, rideshare">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - {{ config('app.name') }}</title>
    <noscript>JavaScript must be enabled to use {{ config('app.name') }}.</noscript>
    <script>
        window.userId = {{ auth()->user()->id ?? 'null' }}
    </script>
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
