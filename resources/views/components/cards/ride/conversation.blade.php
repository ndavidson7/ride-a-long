@props(['ride', 'messageWrappers'])

<div id="ride-conversation" {{ $attributes->merge(['class' => 'card']) }}>
    <h3 class="card-header">Chat</h3>
    <div class="card-body overflow-scroll" id="message-history" style="position: relative; height: 400px">
        @foreach ($messageWrappers as $messageWrapper)
            <x-messages.wrapper :$messageWrapper />
        @endforeach
    </div>
    <form class="card-footer text-body-secondary d-flex justify-content-start align-items-center p-3" id="message-form"
        action="{{ route('conversations.update', $ride->conversation) }}" method="POST" autocomplete="off">
        @method('PUT')
        @csrf
        @if ($pfpUrl = auth()->user()->pfp_url)
            <img src="{{ $pfpUrl }}" alt="My profile picture" style="width: 50px; height: 50px;">
        @endif
        <input class="form-control form-control-lg" name="message" type="text" placeholder="Type message">
        <button class="btn ms-1" type="submit"><i class="bi bi-send-fill fs-4"></i></button>
    </form>
</div>

@php
    $senderOther = ['id' => 0, 'name' => '', 'pfp_url' => ''];
    $senderSelf = auth()->user()->getParticipantDetails();

    $messageWrapperOther = ['sender' => $senderOther, 'datetime' => '', 'message_chain' => []];
    $messageWrapperSelf = ['sender' => $senderSelf, 'datetime' => '', 'message_chain' => []];
@endphp

<template id="message-wrapper-template-other">
    <x-messages.wrapper :messageWrapper="$messageWrapperOther" />
</template>

<template id="message-template-other">
    <x-messages.message :sender="$senderOther" :messageInfo="['message' => '', 'timestamp' => '']" />
</template>

<template id="message-wrapper-template-self">
    <x-messages.wrapper :messageWrapper="$messageWrapperSelf" />
</template>

<template id="message-template-self">
    <x-messages.message :sender="$senderSelf" :messageInfo="['message' => '', 'timestamp' => '']" />
</template>

<template id="divider-template">
    <div class="divider d-flex align-items-center mb-4">
        <p class="content mx-3 mb-0 text-center" style="color: #a2aab7;"></p>
    </div>
</template>
