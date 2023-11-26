@if ($sender->id !== auth()->user()->id)
    <p class="small p-2 ms-3 mb-1 rounded-3 message" style="background-color: #f5f6f7;">{{ $message }}</p>
@else
    <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary message">{{ $message }}</p>
@endif
