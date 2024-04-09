@props(['modalId', 'xData' => '{}'])

<button type="button" aria-controls="{{ $modalId }}" {{-- Prepending openModal() allows additional @click logic to be supplied to component --}}
    {{ $attributes->merge(['@click' => $attributes->prepends('openModal();')]) }} x-data="{
        modalId: '{{ $modalId }}',
    
        openModal() {
            $dispatch('modal:open', { id: this.modalId });
        },
    
        ...{!! $xData !!}
    }">

    {{ $slot }}

</button>
