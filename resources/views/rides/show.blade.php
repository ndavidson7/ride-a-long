<x-layouts.main title="{{ $ride->driver->first_name }} {{ $ride->driver->last_name }}'s ride" :$entries>
    <main class="container py-4">
        <div class="row">
            <div class="col-lg">
                <x-cards.ride-details class="mb-3" :$ride />
            </div>
            @if (in_array($ride->user_relation, ['driver', 'passenger']))
                <div class="col-lg">
                    <x-cards.ride-conversation class="mb-3" :$ride :messageWrappers="json_encode($messageWrappers)" :$users />
                    <x-cards.ride-passengers :$ride class="mb-3" />
                    @if ($ride->user_relation === 'driver')
                        <x-cards.ride-requests :$ride />
                    @endif
                </div>
            @endif
        </div>
    </main>
    <script>
        var ride = @json($ride);
        var users = @json($users);
    </script>
</x-layouts.main>
