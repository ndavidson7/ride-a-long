<x-layouts.main title="View request" :$entries>
    <main>
        <div class="container col-sm-8 col-md-6 col-lg-5 col-xl-4 py-5">
            <h2 class="text-center col-12">Request Details</h2>
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
            <form action="{{ route('requests.update', $request->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="d-flex justify-content-start gap-2">
                    <button type="submit" class="btn btn-success" name="response" value="1">Accept</button>
                    <button type="submit" class="btn btn-danger" name="response" value="0">Deny</button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.main>
