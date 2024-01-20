<x-layouts.app title="Your alerts"> {{-- :$entries> --}}
    <main>
        <div class="container-xl py-4">
            <h1>New ride alerts</h1>
            <div class="table-responsive-md">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Origin</th>
                            <th scope="col">Destination</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Active</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($newRideAlerts as $newRideAlert)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $newRideAlert->origin_formatted }}</td>
                                <td>{{ $newRideAlert->destination_formatted }}</td>
                                <td>{{ $newRideAlert->duration }}</td>
                                <td>
                                    @if ($newRideAlert->is_active)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @endif
                                </td>
                                <td class="d-flex flex-wrap gap-1">
                                    <a class="btn btn-primary"
                                        href="{{ route('new-ride-alerts.edit', $newRideAlert) }}">Edit</a>
                                    <form action="{{ route('new-ride-alerts.destroy', $newRideAlert) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="col" colspan="6">
                                <a class="btn btn-primary" href="{{ route('new-ride-alerts.create') }}">Create new
                                    alert</a>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <h1 class="">Seat open alerts</h1>
            {{-- TODO: Ride card for each ride with a seat open alert --}}
        </div>
    </main>
</x-layouts.app>
