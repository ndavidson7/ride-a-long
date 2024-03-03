@props(['modalId', 'xData' => '{}'])

<div role="button" aria-controls="{{ $modalId }}" tabindex="0" {{ $attributes }} x-data="{
    modalId: '{{ $modalId }}',

    openModal() {
        $dispatch('modal:open', { id: this.modalId });
    },

    ...{!! $xData !!}
}"
    @click="openModal" @keydown.enter="openModal">

    {{ $slot }}

</div>
