<x-layouts.main title="View request" :$entries>
    <main>
        <div class="container col-sm-8 col-md-6 col-lg-5 col-xl-4 pt-3">
            <h2 class="text-center mb-3">Request Details</h2>
            <div class="row">
                <x-map />
            </div>
            @if ($request->pickup->address)
                <div class="row mb-3">
                    <h3>Pickup address</h3>
                    <p>{{ $request->pickup->address }}</p>
                </div>
            @endif
            @if ($request->dropoff->address)
                <div class="row mb-3">
                    <h3>Dropoff address</h3>
                    <p>{{ $request->dropoff->address }}</p>
                </div>
            @endif
            @if ($request->message)
                <div class="row mb-3">
                    <h3>Message</h3>
                    <p>{{ $request->message }}</p>
                </div>
            @endif
            @if ($request->ride->user_relation === 'driver')
                <form action="{{ route('requests.update', $request->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="d-flex justify-content-start gap-2">
                        <button type="submit" class="btn btn-success" name="response" value="1">Accept</button>
                        <button type="submit" class="btn btn-danger" name="response" value="0">Deny</button>
                    </div>
                </form>
            @elseif ($request->response !== null)
                <div class="row mb-3">
                    <h3>Response</h3>
                    <p>{{ $request->response ? 'Accepted' : 'Denied' }}</p>
                    <form action="{{ route('requests.destroy', $request->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Mark as Read</button>
                    </form>
                </div>
            @endif
        </div>
    </main>
</x-layouts.main>
