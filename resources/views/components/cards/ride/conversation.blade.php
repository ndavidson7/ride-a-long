@props(['ride', 'messageWrappers'])

<div>
    <x-typography.h2>Chat</x-typography.h2>

    <div class="min-h-96 relative overflow-y-auto">
        @foreach ($messageWrappers as $messageWrapper)
            <x-messages.wrapper :$messageWrapper />
        @endforeach
    </div>

    <x-form class="flex items-center gap-2" action="{{ route('conversations.update', $ride->conversation) }}"
        method="PUT" autocomplete="off">
        <x-pfp class="size-12 hidden flex-shrink-0 md:block" :user="auth()->user()" />
        <x-inputs.input class="flex-grow outline-none" name="message" placeholder="Type message" unstyled />
        <x-button class="text-gray-400 hover:text-gray-500" unstyled><x-fas-paper-plane class="size-6" /></x-button>
    </x-form>
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
