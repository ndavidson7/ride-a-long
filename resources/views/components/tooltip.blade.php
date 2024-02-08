<div class="inline-block w-auto" x-data="{
    visible: {{ $visible ? 'true' : 'false' }},
}">

    <div x-ref="content" @mouseenter="visible = true" @mouseleave="visible = false" @click="visible = true"
        @click.outside="visible = false" {{ $attributes->class(['cursor-pointer']) }}>
        {{ $slot }}
    </div>

    <div class="text-wrap max-w-prose rounded bg-black bg-opacity-90 px-2 py-1 text-xs text-white" x-show="visible"
        x-anchor.{{ $position }}="$refs.content" x-transition x-cloak>{{ $text }}</div>

</div>
