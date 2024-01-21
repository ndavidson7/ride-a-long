<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Nicholas Davidson">
    <meta name="description" content="{{ config('app.name') }}: Carpooling made easy.">
    <meta name="keywords" content="{{ config('app.name') }}, ridealong, carpool, rideshare">
    <title>{{ config('app.name') }} - Carpooling made easy</title>
    @vite(['resources/css/app.css', 'resources/css/views/landing.css', 'resources/js/app.js', 'resources/js/views/landing.js'])
</head>

<body>
    {{-- Header --}}
    <header class="d-flex flex-column justify-content-center align-items-center px-sm-5 px-3 text-center text-white">
        <a class="btn btn-cta" href="{{ route('sessions.create') }}" role="button">Log In</a>
        <h1 class="display-4 fw-bold">Share the Journey with {{ config('app.name') }}</h1>
        <p class="lead">Carpooling made simple, affordable, and convenient for everyone.</p>
    </header>

    {{-- Features Section --}}
    <section class="features container-fluid d-flex flex-column row-gap-5 text-md-start text-center">
        <div class="row justify-content-center align-items-center column-gap-5 row-gap-3 row-gap-md-0">
            <div class="col-md-5">
                <h2 class="display-5 fw-bold">Long-Distance Rides</h2>
                <p class="lead">Connect with drivers going the distance you need, whether it's
                    cross-city or cross-country.</p>
            </div>
            <div class="col-8 col-sm-6 col-md-5 order-md-0 order-first text-center">
                <img class="img-fluid" src="{{ asset('images/navigator.svg') }}" alt="Car driving on a road">
            </div>
        </div>
        <div class="row justify-content-center align-items-center column-gap-5 row-gap-3 row-gap-md-0">
            <div class="col-8 col-sm-6 col-md-5 text-center">
                <img class="img-fluid" src="{{ asset('images/trip.svg') }}" alt="Two people by a car">
            </div>
            <div class="col-md-5">
                <h2 class="display-5 fw-bold">Friendly Drivers</h2>
                <p class="lead">Our drivers are everyday people sharing their rides, not professional
                    taxi drivers.</p>
            </div>
        </div>
        <div class="row justify-content-center align-items-center column-gap-5 row-gap-3 row-gap-md-0">
            <div class="col-md-5">
                <h2 class="display-5 fw-bold">Cost-Effective Travel</h2>
                <p class="lead">Optional passenger contributions ensure fair sharing of gas costs, saving
                    you money.</p>
            </div>
            <div class="col-8 col-sm-6 col-md-5 order-md-0 order-first text-center">
                <img class="img-fluid" src="{{ asset('images/savings.svg') }}" alt="Putting money in piggy bank">
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="container-md">
        <div class="row mb-3 text-center">
            <h2 class="display-5 fw-bold">Frequently Asked Questions</h2>
        </div>
        <div class="row">
            <div class="accordion" id="faq-accordion">
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed fs-3" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" type="button" aria-expanded="false"
                            aria-controls="collapseOne">
                            How does this compare to other ridesharing services?
                        </button>
                    </h3>
                    <div class="accordion-collapse collapse" id="collapseOne" data-bs-parent="#faq-accordion">
                        <div class="accordion-body fs-4">
                            Other services are essentially modern-day taxis, where the drivers await requests and earn a
                            profit from each ride.
                            {{ config('app.name') }}, however, is a platform for facilitating carpooling, where the
                            drivers have
                            planned a trip and have extra seats available.
                            This isn't to say {{ config('app.name') }} drivers can't profit, but being a driver isn't a
                            viable
                            full-time job. It's simply a way of offsetting the cost of gas—and maybe making some friends
                            in the process!
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed fs-3" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" type="button" aria-expanded="false"
                            aria-controls="collapseTwo">
                            What are the safety measures in place?
                        </button>
                    </h3>
                    <div class="accordion-collapse collapse" id="collapseTwo" data-bs-parent="#faq-accordion">
                        <div class="accordion-body">

                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed fs-3" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" type="button" aria-expanded="false"
                            aria-controls="collapseThree">
                            How are the costs calculated and shared among passengers?
                        </button>
                    </h3>
                    <div class="accordion-collapse collapse" id="collapseThree" data-bs-parent="#faq-accordion">
                        <div class="accordion-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Call to Action --}}
    <section class="text-center">
        <h2 class="display-5 fw-bold">Ready to Share Your Ride or Find One?</h2>
        <p class="lead">Join {{ config('app.name') }} today and embark on a journey of affordable and friendly
            carpooling.</p>
        <a class="btn btn-lg btn-cta" href="{{ route('users.create') }}" role="button">Sign Up Now</a>
    </section>

    {{-- Footer --}}
    <footer>
        <div class="d-flex justify-content-center align-items-center column-gap-3 flex-wrap p-2">
            <a href="#">About</a>
            <a href="#">Contact</a>
            <a href="#">Privacy Policy</a>
        </div>
        <small class="text-white">Copyright &copy; 2024 | {{ config('app.name') }}</small>
    </footer>
</body>

</html>
