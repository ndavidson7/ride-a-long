@props(['options' => []])

@php
    $options = (object) array_merge(
        [
            'dateFormat' => 'Z',
            'altInput' => true,
            'altFormat' => 'l, F J h:i K',
            'minDate' => 'today',
        ],
        $options,
    );
@endphp

<x-inputs.input type="text" x-data="{
    picker: null,

    init() {
        this.picker = flatpickr($el, {{ Js::from($options) }});
    }
}" {{ $attributes->merge(['placeholder' => 'Choose a date']) }} />
