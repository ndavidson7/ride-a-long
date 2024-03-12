@props(['modalId', 'xData' => '{}'])

{{-- <button type="button" aria-controls="{{ $modalId }}"
    {{ $attributes->class(['inline-flex', 'items-center', 'justify-center']) }} x-data="{
        openModal() {
            $dispatch('modal:open', { id: '{{ $modalId }}' });
        }
    }"
    @click="openModal">

    {{ $slot }}

</button> --}}

<button type="button" aria-controls="{{ $modalId }}"
    {{ $attributes->merge(['@click' => $attributes->prepends('openModal();')]) }} x-data="{
        modalId: '{{ $modalId }}',
    
        openModal() {
            $dispatch('modal:open', { id: this.modalId });
        },
    
        ...{!! $xData !!}
    }">

    {{ $slot }}

</button>
