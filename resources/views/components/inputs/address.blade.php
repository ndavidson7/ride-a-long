@php
    $name = $attributes->get('name');
    // if (empty($address->getAttributes()) && !is_null(old($name, request($name)))) {
    //     $address->fill([
    //         'address' => old($name, request($name)),
    //         'city' => old($name . '-city', request($name . '-city')),
    //         'state' => old($name . '-state', request($name . '-state')),
    //         'country' => old($name . '-country', request($name . '-country')),
    //         'latitude' => old($name . '-latitude', request($name . '-latitude')),
    //         'longitude' => old($name . '-longitude', request($name . '-longitude')),
    //     ]);
    // }
@endphp

<div x-data="{
    resultsShown: false,
    selected: false,
    address: {
        formattedAddress: '{{ $address?->address }}',
        city: '{{ $address?->city }}',
        state: '{{ $address?->state }}',
        country: '{{ $address?->country }}',
        latitude: '{{ $address?->latitude }}',
        longitude: '{{ $address?->longitude }}'
    }
    {{-- address: $persist({
        formattedAddress: '{{ $address?->address }}',
        city: '{{ $address?->city }}',
        state: '{{ $address?->state }}',
        country: '{{ $address?->country }}',
        latitude: '{{ $address?->latitude }}',
        longitude: '{{ $address?->longitude }}'
    }).as('{{ $name }}') --}}
}">
    <x-inputs.input form="none" ::value="address.formattedAddress" {{ $attributes }} autocomplete="off" x-data="{
        checkIfSelected() {
            resultsShown = false;
            if (!selected) {
                $el.value = '';
                address = {};
            }
        }
    }"
        x-init="Radar.ui.autocomplete({
            container: $el,
            near: {{ $near ?? 'null' }},
            {{-- TODO: Fix this ^ --}}
            debounceMS: {{ $debounceMS }},
            minCharacters: {{ $minCharacters }},
            limit: {{ $limit }},
            disabled: {{ $disabled ? 'true' : 'false' }},
            layers: {{ Js::from($layers) }},
            countryCode: '{{ $countryCode }}',
            onSelection: (selectedAddress) => {
                console.log(selectedAddress);
                selected = true;
                address = selectedAddress;
            },
            onResults: (results) => {
                resultsShown = results.length > 0;
            },
            onError: (error) => {
                console.error(error);
            }
        })" @input="selected = false"
        @keydown.enter="if (resultsShown) { 
            $event.preventDefault();
            checkIfSelected();
        }"
        @blur="checkIfSelected" />
    @foreach ($addressComponents as $addressComponent)
        <x-buk-input name="{{ $name }}-{{ $addressComponent }}" type="hidden" ::value="address.{{ $addressComponent }}" />
    @endforeach
</div>
