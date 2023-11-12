<x-layouts.main title="View request" :$entries>
    <main class="py-4">
        <div class="container col-sm-10 col-md-8 col-lg-6 mb-3">
            <h2 class="text-center mb-3">Preview</h2>
            <div class="row">
                <x-map />
            </div>
        </div>
        <div class="container col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <h2 class="text-center mb-3">Request Details</h2>
            @if ($request->ride->detours_allowed)
                <h3>Pickup address</h3>
                <div class="row mb-3">
                    @if ($request->pickup)
                        <p>{{ $request->pickup->address }}</p>
                    @else
                        <p>None</p>
                    @endif
                </div>
            @endif
            @if ($request->ride->detours_allowed)
                <h3>Dropoff address</h3>
                <div class="row mb-3">
                    @if ($request->dropoff)
                        <p>{{ $request->dropoff->address }}</p>
                    @else
                        <p>None</p>
                    @endif
                </div>
            @endif
            @if ($request->message)
                <div class="row mb-3">
                    <h3>Message</h3>
                    <p>{{ $request->message }}</p>
                </div>
            @endif
            @if ($request->ride->user_relation === 'driver' && $request->response === null)
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
                    @if ($request->user_id === auth()->user()->id)
                        <form action="{{ route('requests.destroy', $request->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">Mark as Read</button>
                        </form>
                    @endif
                </div>
            @endif
            <script>
                var request = @json($request);
            </script>
        </div>
    </main>
</x-layouts.main>
