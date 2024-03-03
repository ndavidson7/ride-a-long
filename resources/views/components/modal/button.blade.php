@props(['modalId'])

<button type="button" aria-controls="{{ $modalId }}"
    {{ $attributes->class(['inline-flex', 'items-center', 'justify-center']) }} x-data="{
        openModal() {
            $dispatch('modal:open', { id: '{{ $modalId }}' });
        }
    }"
    @click="openModal">

    {{ $slot }}

</button>
