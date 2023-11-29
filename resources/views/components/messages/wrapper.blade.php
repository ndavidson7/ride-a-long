@php
    $sender = $messageWrapper['sender'];
    $datetime = $messageWrapper['datetime'];
    $messageChain = $messageWrapper['message_chain'];
@endphp

@if ($sender['id'] !== auth()->user()->id)
    {{-- other --}}
    <div class="d-flex flex-column align-items-start pt-1" data-sender="{{ $sender['id'] }}">
        <div class="d-flex align-items-center gap-2 mb-2">
            @if (isset($sender['pfp_url']))
                <a href="{{ route('profile.show', $sender['id']) }}" style="width: 50px; height: 50px;"><img
                        src="{{ $sender['pfp_url'] }}" alt="User's profile picture"
                        class="img-fluid rounded-circle shadow-lg"></a>
            @endif
            <div class="d-flex flex-column justify-content-center align-items-start">
                <a href="{{ route('profile.show', $sender['id']) }}"
                    class="text-reset text-decoration-none name">{{ $sender['name'] }}</a>
                <small class="text-muted timestamp calendar">{{ $datetime }}</small>
            </div>
        </div>
        <div class="d-flex flex-column align-items-start message-chain">
            @foreach ($messageChain as $messageInfo)
                <x-messages.message :$sender :$messageInfo />
            @endforeach
        </div>
    </div>
@else
    {{-- self --}}
    <div class="d-flex flex-column align-items-end pt-1" data-sender="{{ $sender['id'] }}">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="d-flex flex-column justify-content-center align-items-end">
                <a href="{{ route('profile.show', $sender['id']) }}"
                    class="text-reset text-decoration-none name">{{ $sender['name'] }}</a>
                <small class="text-muted timestamp calendar">{{ $datetime }}</small>
            </div>
            @if (isset($sender['pfp_url']))
                <a href="{{ route('profile.show', $sender['id']) }}" style="width: 50px; height: 50px;"><img
                        src="{{ $sender['pfp_url'] }}" alt="My profile picture"
                        class="img-fluid rounded-circle shadow-lg"></a>
            @endif
        </div>
        <div class="d-flex flex-column align-items-end message-chain">
            @foreach ($messageChain as $messageInfo)
                <x-messages.message :$sender :$messageInfo />
            @endforeach
        </div>
    </div>
@endif
