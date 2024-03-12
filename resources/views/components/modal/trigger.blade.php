@props(['modalId', 'xData' => '{}'])

{{-- @php
    $attributes = $attributes->merge(['@click' => $attributes->prepends('openModal();')]);
@endphp

<div role="button" aria-controls="{{ $modalId }}" tabindex="0" {{ $attributes }} x-data="{
    modalId: '{{ $modalId }}',

    openModal() {
        $dispatch('modal:open', { id: this.modalId });
    },

    ...{!! $xData !!}
}"
    @keydown.enter="{{ $attributes->get('@click') }}">

    {{ $slot }}

</div> --}}

<x-modal.button :$modalId :$xData {{ $attributes->class(['w-full']) }}>
    <span class="block">
        {{ $slot }}
    </span>
</x-modal.button>
