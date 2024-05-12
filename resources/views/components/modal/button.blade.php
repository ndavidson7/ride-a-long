@props(['target', 'xData' => '{}'])

{{-- Target is the id of the modal being controlled by this button --}}
<button data-target="{{ $target }}" type="button" aria-controls="{{ $target }}" {{-- Prepending openModal() allows additional @click logic to be supplied to component --}}
    {{ $attributes->merge(['@click' => $attributes->prepends('openModal();')]) }} x-data="{
        openModal() {
                $dispatch('modal:open');
            },
    
            ...{!! $xData !!}
    }">

    {{ $slot }}

</button>
