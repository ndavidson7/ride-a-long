@props(['id', 'title' => '', 'xData' => '{}'])

<template id="{{ $id }}" x-teleport="body" x-data="{ open: false, ...{!! $xData !!} }" @keydown.escape.window="open = false"
    @modal:open.window="open = $event.detail.id === $el.id"
    @modal:update.window="if ($event.detail.id === $el.id) update($event.detail.args);" :class="{ 'z-40': open }">
    <div class="fixed left-0 top-0 z-[99] flex h-screen w-screen items-center justify-center" x-show="open" x-cloak>
        {{-- Background blur --}}
        <div class="absolute inset-0 h-full w-full bg-white bg-opacity-70 backdrop-blur-sm" x-show="open"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open=false"></div>

        {{-- Modal --}}
        <div role="dialog"
            {{ $attributes->class([
                'relative',
                'w-full',
                'max-h-full',
                'border',
                'border-neutral-200',
                'bg-white',
                'px-2',
                'py-3',
                'sm:px-4',
                'md:px-6',
                'md:py-5',
                'shadow-lg',
                'sm:rounded-lg',
                'overflow-y-auto',
                'space-y-4',
            ]) }}
            x-show="open" x-trap.inert.noscroll="open" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 -translate-y-2 sm:scale-95" @click.outside="open=false">

            <div class="flex items-center justify-between pb-3">

                {{-- Title --}}
                <h1 class="text-lg font-semibold">{{ $title }}</h1>

                {{-- Close button --}}
                <button class="size-8 rounded-full text-gray-600 hover:bg-gray-50 hover:text-gray-800"
                    @click="open=false">
                    <x-fas-xmark class="size-5 m-auto" />
                </button>

            </div>

            <div {{ $body->attributes->class(['relative', 'w-auto']) }}>
                {{ $body }}
            </div>

            <div
                {{ $footer->attributes->class(['flex', 'flex-col-reverse', 'sm:flex-row', 'sm:justify-end', 'gap-2']) }}>
                {{ $footer }}
            </div>

        </div>
    </div>
</template>
