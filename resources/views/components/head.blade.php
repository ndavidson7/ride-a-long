<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Nicholas Davidson">
    <meta name="description" content="Ride-A-Long: Carpooling made easy. {{ $title }}">
    <meta name="keywords" content="Ride-a-long, ridealong, carpool, rideshare">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ride-A-Long - {{ $title }}</title>
    <noscript>JavaScript must be enabled to use Ride-A-Long.</noscript>
    <script>
        window.userId = {{ auth()->user()->id ?? 'null' }}
    </script>
    @routes
    @vite(array_merge(['resources/scss/app.scss', 'resources/js/app.js'], $entries))
</head>
