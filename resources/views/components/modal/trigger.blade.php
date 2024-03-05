@props(['modalId', 'xData' => '{}'])

<div role="button" aria-controls="{{ $modalId }}" tabindex="0"
    {{ $attributes->merge(['@click' => $attributes->prepends('openModal();')]) }} x-data="{
        modalId: '{{ $modalId }}',
    
        openModal() {
            $dispatch('modal:open', { id: this.modalId });
        },
    
        ...{!! $xData !!}
    }"
    @keydown.enter="openModal">

    {{ $slot }}

</div>
