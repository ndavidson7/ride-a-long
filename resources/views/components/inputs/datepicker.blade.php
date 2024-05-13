@props(['options' => []])

<x-inputs.input type="text" x-data="{
    picker: null,

    init() {
        this.picker = flatpickr($el, {
            dateFormat: 'Z',
            altInput: true,
            altFormat: 'l, F J h:i K',
            minDate: dayjs.utc().format(),
            ...{{ Js::from((object) $options) }}
        });
    }
}" {{ $attributes->merge(['placeholder' => 'Choose a date']) }} />
