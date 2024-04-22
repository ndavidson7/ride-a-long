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

        console.log(this.messageWrappers);

        $nextTick(() => this.scrollToBottom());
    },
}">
    <x-typography.h2>Chat</x-typography.h2>

    <div class="mb-3 h-full max-h-96 space-y-2 overflow-y-auto md:px-1" x-ref="messages">
        <template x-for="messageWrapper in messageWrappers"
            :key="`${messageWrapper.sender.id}-${messageWrapper.created_at}`">
            {{-- Message wrapper --}}
            <div class="relative flex flex-col gap-0.5" x-data="{ self: messageWrapper.sender.id === {{ Js::from(auth()->user()->id) }} }"
                :class="self ? 'items-end' : 'items-start'">
                <a class="size-7 md:size-9 absolute top-0 z-10 rounded-full" :class="self ? 'right-0' : 'left-0'"
                    :href="route('users.show', messageWrapper.sender.id)">
                    <template x-if="messageWrapper.sender.pfp_url">
                        <img class="size-full rounded-full" :src="messageWrapper.sender.pfp_url"
                            :alt="`${messageWrapper.sender.name}'s profile picture`">
                    </template>
                    <template x-if="!messageWrapper.sender.pfp_url">
                        <x-fas-circle-user class="size-full rounded-full bg-white text-gray-400" />
                    </template>
                </a>
                <template x-for="(message, index) in messageWrapper.messages" :key="message.created_at">
                    {{-- Message --}}
                    <div
                        :class="{
                            'relative': true,
                            'opacity-50': message
                                .status === 'sent',
                            'pr-8 md:pr-10': self,
                            'pl-8 md:pl-10': !self
                        }">
                        <template x-if="index === 0">
                            <div class="mb-1 flex flex-wrap items-end gap-1">
                                <a class="text-xs/none font-medium" :class="self && 'order-2'"
                                    :href="route('users.show', messageWrapper.sender.id)"
                                    x-text="messageWrapper.sender.name"></a>
                                <time class="text-[8px] leading-none text-gray-600 md:text-[10px]"
                                    x-text="dayjs(messageWrapper.created_at).calendar()"></time>
                            </div>
                        </template>
                        <p class="peer w-fit rounded-2xl px-2.5 py-1 text-sm"
                            :class="self ? 'bg-blue-500 text-white ms-auto' : 'bg-gray-300'" x-text="message.body">
                        </p>
                        <template x-if="index !== 0">
                            <time
                                class="invisible absolute top-1/2 -translate-y-1/2 text-[8px] text-gray-600 peer-hover:visible md:text-[10px]"
                                :class="self ? 'right-0' : 'left-0'"
                                x-text="dayjs(message.created_at).format('LT')"></time>
                        </template>
                    </div>
                </template>
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
