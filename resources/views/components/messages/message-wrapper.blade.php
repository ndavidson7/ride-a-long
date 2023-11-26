@php
    $sender = $messageWrapper->sender;
    $messageChain = $messageWrapper->message_chain;
    $timestamp = $messageWrapper->timestamp;
@endphp

@if ($sender->id !== auth()->user()->id)
    {{-- other --}}
    <div class="d-flex justify-content-start pt-1">
        @if (isset($sender->pfp_url))
            <a href="{{ route('profile.show', $sender->id) }}" style="width: 45px; height: 45px;"><img
                    src="{{ $sender->pfp_url }}" alt="{{ $sender->name }}'s profile picture"
                    class="img-fluid rounded-circle shadow-lg"></a>
        @endif
        <div class="d-flex flex-column align-items-start">
            <a href="{{ route('profile.show', $sender->id) }}"
                class="small ms-3 mb-1 text-reset text-decoration-none name">{{ $sender->name }}</a>
            <div class="d-flex flex-column align-items-start message-chain">
                @foreach ($messageChain as $message)
                    <x-messages.message :$sender :$message />
                @endforeach
            </div>
            <p class="small ms-3 mb-3 text-muted timestamp">{{ $timestamp }}</p>
        </div>
    </div>
@else
    {{-- self --}}
    <div class="d-flex justify-content-end pt-1">
        <div class="d-flex flex-column align-items-end">
            <a href="{{ route('profile.show', $sender->id) }}"
                class="small me-3 mb-1 text-reset text-decoration-none name">{{ $sender->name }}</a>
            <div class="d-flex flex-column align-items-end message-chain">
                @foreach ($messageChain as $message)
                    <x-messages.message :$sender :$message />
                @endforeach
            </div>
            <p class="small me-3 mb-3 text-muted timestamp">{{ $timestamp }}</p>
        </div>
        @if (isset($sender->pfp_url))
            <a href="{{ route('profile.show', $sender->id) }}" style="width: 45px; height: 45px;"><img
                    src="{{ $sender->pfp_url }}" alt="My profile picture"
                    class="img-fluid rounded-circle shadow-lg"></a>
        @endif
    </div>
@endif
