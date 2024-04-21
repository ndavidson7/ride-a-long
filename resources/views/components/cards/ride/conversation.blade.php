@props(['ride', 'messageWrappers'])

<div x-data="{
    messageWrappers: {{ Js::from($messageWrappers) }},

    get isAtBottom() {
        return $refs.messages.scrollTop + $refs.messages.clientHeight >= $refs.messages.scrollHeight;
    },

    scrollToBottom() {
        $refs.messages.scrollTop = $refs.messages.scrollHeight - $refs.messages.clientHeight;
    },

    addMessage(message, status = 'received') {
        const { sender, body, created_at } = message;
        const newMessage = { body, created_at, status };
        const lastMessageWrapper = this.messageWrappers.length ? this.messageWrappers[this.messageWrappers.length - 1] : null;

        let messagesLength = 1;
        if (lastMessageWrapper && lastMessageWrapper.sender.id === sender.id) {
            messagesLength = lastMessageWrapper.messages.push(newMessage);
        } else {
            this.messageWrappers.push({
                sender,
                created_at,
                messages: [newMessage],
            });
        }

        if (this.isAtBottom) $nextTick(() => this.scrollToBottom());

        {{-- Return the Proxy form of the new message so that updating status triggers reactivity --}}
        return this.messageWrappers[this.messageWrappers.length - 1].messages[messagesLength - 1];
    },

    async sendMessage(e) {
        {{--
            TODO: Immediately reset form and append message with loading state (transparent/muted text)
            and reset to normal state when response is ok 
        --}}
        const data = new FormData(e.target);

        {{-- Clear message input --}}
        $refs.messageForm.reset();
        const message = this.addMessage({ sender: {{ Js::from(auth()->user()->getParticipantDetails()) }}, body: data.get('message'), created_at: new Date().toISOString() }, 'sent');

        const response = await fetch(e.target.action, {
            method: 'POST',
            body: data,
            headers: { Accept: 'application/json' },
        });

        if (response.ok) {
            message.status = 'received';
        } else {
            const data = await response.json();
            alert(data.message ?? 'Something went wrong.');
        }
    },

    init() {
        Echo.private('mc-chat-conversation.{{ Js::from($ride->conversation->id) }}').listen(
            '.Musonza\\Chat\\Eventing\\MessageWasSent',
            (e) => {
                if (e.message.sender.id !== {{ Js::from(auth()->user()->id) }})
                    this.addMessage(e.message);
            }
        );

        $nextTick(() => this.scrollToBottom());
    },
}">
    <x-typography.h2>Chat</x-typography.h2>

    <div class="relative mb-3 h-full max-h-96 space-y-2 overflow-y-auto md:px-2" x-ref="messages">
        <template x-for="messageWrapper in messageWrappers"
            :key="`${messageWrapper.sender.id}-${messageWrapper.created_at}`">
            {{-- Message wrapper --}}
            <div class="flex flex-col gap-2" x-data="{ self: messageWrapper.sender.id === {{ Js::from(auth()->user()->id) }} }" :class="self ? 'items-end' : 'items-start'">
                {{-- User --}}
                <div class="flex items-center gap-2">
                    <div class="size-10" :class="self && 'order-2'">
                        <template x-if="messageWrapper.sender.pfp_url">
                            <img class="size-full rounded-full" :src="messageWrapper.sender.pfp_url"
                                :alt="`${messageWrapper.sender.name}'s profile picture`">
                        </template>
                        <template x-if="!messageWrapper.sender.pfp_url">
                            <x-fas-circle-user class="size-full rounded-full bg-white text-gray-400" />
                        </template>
                    </div>
                    <div class="flex flex-col justify-center" :class="self ? 'items-end' : 'items-start'">
                        <a :href="route('users.show', messageWrapper.sender.id)"
                            x-text="messageWrapper.sender.name"></a>
                        <time class="text-sm text-gray-600" x-text="dayjs(messageWrapper.created_at).calendar()"></time>
                    </div>
                </div>
                {{-- Message chain --}}
                <div class="flex flex-col gap-1" :class="self ? 'items-end' : 'items-start'">
                    <template x-for="message in messageWrapper.messages" :key="message.created_at">
                        {{-- Message --}}
                        <div class="flex items-center gap-2" :class="message.status === 'sent' && 'opacity-50'">
                            <p class="peer rounded-3xl px-3 py-2"
                                :class="self ? 'bg-blue-500 text-white' : 'bg-gray-300 order-2'" x-text="message.body">
                            </p>
                            <time class="invisible text-xs text-gray-600 peer-hover:visible"
                                x-text="dayjs(message.created_at).format('LT')"></time>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <x-form class="flex items-center gap-2" action="{{ route('conversations.update', $ride->conversation) }}"
        method="PUT" autocomplete="off" @submit.prevent="sendMessage" x-ref="messageForm">
        <x-pfp class="size-12 hidden flex-shrink-0 md:block" :user="auth()->user()" />
        <x-inputs.input class="flex-grow outline-none" name="message" placeholder="Type message" unstyled />
        <x-button class="text-gray-400 hover:text-gray-500" unstyled><x-fas-paper-plane class="size-6" /></x-button>
    </x-form>
</div>
