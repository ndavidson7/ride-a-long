<div class="d-flex align-items-center gap-2">
    @if ($sender['id'] !== auth()->user()->id)
        <small class="text-muted invisible timestamp">{{ $messageInfo['timestamp'] }}</small>
        <p class="p-2 mb-1 rounded-3 message" style="background-color: #f5f6f7;">{{ $messageInfo['message'] }}</p>
    @else
        <p class="p-2 mb-1 text-white rounded-3 bg-primary message">{{ $messageInfo['message'] }}</p>
        <small class="text-muted invisible timestamp">{{ $messageInfo['timestamp'] }}</small>
    @endif
</div>
