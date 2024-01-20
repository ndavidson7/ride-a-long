<x-layouts.app title="{{ $ride->driver->name }}'s ride" :$entries>
    <main class="container py-4">
        <div class="row">
            <div class="col-lg">
                <x-cards.ride.details class="mb-3" :$ride />
                <x-cards.ride.driver class="mb-lg-0 mb-3" :$ride />
            </div>
            @if (in_array($ride->user_relation, ['driver', 'passenger']))
                <div class="col-lg">
                    <x-cards.ride.conversation class="mb-3" :$ride :$messageWrappers />
                    <x-cards.ride.passengers class="mb-3" :$ride />
                    @if ($ride->user_relation === 'driver')
                        <x-cards.ride.requests :$ride />
                    @endif
                </div>
            @endif
        </div>
    </main>
    <script>
        var ride = @json($ride);
    </script>
</x-layouts.app>
