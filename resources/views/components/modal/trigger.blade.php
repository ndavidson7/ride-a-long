@props(['modalId', 'xData' => '{}'])

{{--
    This simply wraps modal button's slot with a block-display span as a workaround for Chrome's shitty, hidden, default button styling.
    Doing so also allows us to take advantage of buttons' not-shitty default behaviors and accessibility features.
--}}
<x-modal.button :$modalId :$xData {{ $attributes->class(['w-full']) }}>
    <span class="block">
        {{ $slot }}
    </span>
</x-modal.button>
