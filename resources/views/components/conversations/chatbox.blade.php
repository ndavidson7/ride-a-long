@props(['conversation'])

@php
    $participants = $conversation->getParticipants()->mapWithKeys(function ($participant, $key) {
        $details = collect($participant->getParticipantDetails());
        return [$details['id'] => $details->except('id')];
    });

    $paginator = \App\Services\ChatService::from($conversation)->getMessages();
@endphp

<div x-data="{
    participants: new Map(Object.entries({{ Js::from($participants) }}).map(([key, value]) => [+key, value])),
    messages: {{ Js::from($paginator->items()) }},
    nextPageUrl: {{ Js::from($paginator->nextPageUrl()) }},

    async init() {
        Echo.private('mc-chat-conversation.{{ Js::from($conversation->id) }}').listen(
            '.Musonza\\Chat\\Eventing\\MessageWasSent',
            (e) => {
                if (e.message.sender.id !== {{ Js::from(auth()->user()->id) }})
                    this.addMessage(e.message);
            }
        );

        $nextTick(() => this.scrollToBottom());
    },

    async loadMore() {
        if (!this.nextPageUrl) return;

        const response = await fetch(this.nextPageUrl, {
            headers: { Accept: 'application/json' },
        });
        const json = await response.json();

        const currentTopMessageId = this.messages.at(-1).id;

        this.nextPageUrl = json.next_page_url;
        this.messages.push(...json.data);

        $nextTick(() => $refs.messages.scrollTop = $refs.messages.querySelector(`[data-id='${currentTopMessageId}']`).getBoundingClientRect().top - $refs.messages.getBoundingClientRect().top + $refs.messages.scrollTop - 20);
    },

    get messageWrappers() {
        return this.messages.toReversed().reduce((acc, message) => {
            const lastMessageWrapper = acc.length ? acc[acc.length - 1] : null;

            if (lastMessageWrapper && lastMessageWrapper.sender === message.sender) {
                lastMessageWrapper.messages.push(message);
            } else {
                acc.push({
                    sender: message.sender,
                    messages: [message],
                });
            }

            return acc;
        }, []);
    },

    get isAtBottom() {
        return $refs.messages.scrollTop + $refs.messages.clientHeight >= $refs.messages.scrollHeight - 20;
    },

    scrollToBottom() {
        $refs.messages.scrollTop = $refs.messages.scrollHeight;
    },

    addMessage(message, status = 'received') {
        const { id = (Math.random() + 1).toString(36).substring(7), sender, body, created_at } = message;
        const newMessage = { id, sender: sender.id, body, created_at, status };

        this.messages.unshift(newMessage);

        if (this.isAtBottom) $nextTick(() => this.scrollToBottom());

        {{-- Return the Proxy form of the new message so that updating status triggers reactivity --}}
        return this.messages[0];
    },

    async sendMessage(e) {
        const data = new FormData(e.target);

        {{-- Clear message input --}}
        $refs.messageForm.reset();

        {{-- Add message --}}
        const message = this.addMessage({ sender: { id: {{ Js::from(auth()->user()->id) }} }, body: data.get('message'), created_at: new Date().toISOString() }, 'sent');

        const response = await fetch(e.target.action, {
            method: 'POST',
            body: data,
            headers: { Accept: 'application/json' },
        });

        const json = await response.json();

        if (response.ok) {
            message.id = json.id;
            message.status = 'received';
        } else {
            alert(json.message ?? 'Something went wrong.');
        }
    },
}">
    <div class="mb-3 h-96 space-y-2 overflow-y-auto md:px-1" x-ref="messages">
        {{-- <div class="grid place-content-center">
            <x-button size="sm" as="button" @click="loadMore" x-show="nextPageUrl" x-cloak>
                Load more
            </x-button>
        </div> --}}
        <div class="invisible" x-intersect="loadMore">
            Load more
        </div>
        <template x-for="(messageWrapper, index) in messageWrappers"
            :key="`${messageWrapper.sender}-${messageWrapper.messages[0].created_at}`">
            {{-- Message wrapper --}}
            <div class="relative flex flex-col gap-0.5" x-data="{
                self: messageWrapper.sender === {{ Js::from(auth()->user()->id) }},
            
                get sender() {
                    return participants.get(messageWrapper.sender);
                }
            }"
                :class="self ? 'items-end' : 'items-start'">
                <a class="size-8 md:size-10 absolute top-0 z-10 rounded-full" :class="self ? 'right-0' : 'left-0'"
                    :href="route('users.show', messageWrapper.sender)">
                    <template x-if="sender.pfp_url">
                        <img class="size-full rounded-full" :src="sender.pfp_url"
                            :alt="`${sender.name}'s profile picture`">
                    </template>
                    <template x-if="!sender.pfp_url">
                        <x-fas-circle-user class="size-full rounded-full bg-white text-gray-400" />
                    </template>
                </a>
                <template x-for="(message, index) in messageWrapper.messages" :key="message.id">
                    {{-- Message --}}
                    <div
                        :class="{
                            'relative': true,
                            'opacity-50': message
                                .status === 'sent',
                            'pr-10 md:pr-12': self,
                            'pl-10 md:pl-12': !self
                        }">
                        <template x-if="index === 0">
                            <div class="mb-1 flex flex-wrap items-end gap-1">
                                <a class="text-xs/none font-medium" :class="self && 'order-2'"
                                    :href="route('users.show', messageWrapper.sender)" x-text="sender.name"></a>
                                <time class="text-[8px] leading-none text-gray-600 md:text-[10px]"
                                    x-text="dayjs(message.created_at).calendar()"></time>
                            </div>
                        </template>
                        <p class="peer w-fit rounded-2xl px-2.5 py-1 text-sm"
                            :class="self ? 'bg-blue-500 text-white ms-auto' : 'bg-gray-300'" x-text="message.body"
                            :data-id="message.id">
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

    <x-form class="flex items-center gap-2" action="{{ route('conversations.update', $conversation) }}" method="PUT"
        autocomplete="off" @submit.prevent="sendMessage" x-ref="messageForm">
        <x-pfp class="size-12 hidden flex-shrink-0 md:block" :user="auth()->user()" />
        <x-inputs.input class="flex-grow outline-none" name="message" placeholder="Type message" unstyled />
        <x-button class="text-gray-400 hover:text-gray-500" unstyled><x-fas-paper-plane class="size-6" /></x-button>
    </x-form>
</div>
