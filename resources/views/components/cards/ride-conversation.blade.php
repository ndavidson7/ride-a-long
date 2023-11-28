<div {{ $attributes->merge(['class' => 'card']) }} id="ride-conversation">
    <h3 class="card-header">Chat</h3>
    <div class="card-body overflow-scroll" id="message-history" style="position: relative; height: 400px">
        @foreach ($messageWrappers as $messageWrapper)
            <x-messages.message-wrapper :$messageWrapper />
        @endforeach
    </div>
    <form class="card-footer text-muted d-flex justify-content-start align-items-center p-3"
        action="{{ route('conversations.update', $ride->conversation) }}" method="POST" id="message-form"
        autocomplete="off">
        @method('PUT')
        @csrf
        @if (isset($users[auth()->user()->id]['pfp_url']))
            <img src="{{ $users[auth()->user()->id]['pfp_url'] }}" alt="My profile picture"
                style="width: 50px; height: 50px;">
        @endif
        <input type="text" class="form-control form-control-lg" name="message" placeholder="Type message">
        <button type="submit" class="btn ms-1"><i class="bi bi-send-fill fs-4"></i></button>
    </form>
</div>

@php
    $senderOther = ['id' => 0, 'name' => '', 'pfp_url' => ''];
    $senderSelf = ['id' => auth()->user()->id, 'name' => '', 'pfp_url' => ''];

    $messageWrapperOther = ['sender' => $senderOther, 'datetime' => '', 'message_chain' => []];
    $messageWrapperSelf = ['sender' => $senderSelf, 'datetime' => '', 'message_chain' => []];
@endphp

<template id="message-wrapper-template-other">
    <x-messages.message-wrapper :messageWrapper="$messageWrapperOther" />
</template>

<template id="message-template-other">
    <x-messages.message :sender="$senderOther" :messageInfo="['message' => '', 'timestamp' => '']" />
</template>

<template id="message-wrapper-template-self">
    <x-messages.message-wrapper :messageWrapper="$messageWrapperSelf" />
</template>

<template id="message-template-self">
    <x-messages.message :sender="$senderSelf" :messageInfo="['message' => '', 'timestamp' => '']" />
</template>

<template id="divider-template">
    <div class="divider d-flex align-items-center mb-4">
        <p class="text-center mx-3 mb-0 content" style="color: #a2aab7;"></p>
    </div>
</template>
