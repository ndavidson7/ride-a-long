@php
    $name = $attributes->get('name');
    if (empty($address->getAttributes()) && !is_null(old($name, request($name)))) {
        $address->fill([
            'address' => old($name, request($name)),
            'city' => old($name . '-city', request($name . '-city')),
            'state' => old($name . '-state', request($name . '-state')),
            'country' => old($name . '-country', request($name . '-country')),
            'latitude' => old($name . '-latitude', request($name . '-latitude')),
            'longitude' => old($name . '-longitude', request($name . '-longitude')),
        ]);
    }
@endphp

<div x-data="{
    address: {
        formattedAddress: '{{ $address?->address }}',
        city: '{{ $address?->city }}',
        state: '{{ $address?->state }}',
        country: '{{ $address?->country }}',
        latitude: '{{ $address?->latitude }}',
        longitude: '{{ $address?->longitude }}'
    },
    resultsShown: false
}">
    <x-inputs.input ::value="address.formattedAddress" {{ $attributes }} autocomplete="off" x-data="{
        checkIfSelected() {
                resultsShown = false;
                if (!address.formattedAddress) {
                    $el.value = '';
                    this.clearAddress();
                    $el.blur();
                }
            },
            clearAddress() {
                if (address.formattedAddress) Object.keys(address).forEach(key => address[key] = '');
            },
            updateAddress(newAddress) {
                Object.keys(address).forEach(key => address[key] = newAddress[key] ?? '');
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
            onSelection: selectedAddress => updateAddress(selectedAddress),
            onResults: results => resultsShown = results.length > 0,
            onError: error => console.error(error)
        })" @input="clearAddress"
        @keydown.enter="if (resultsShown) { 
            $event.preventDefault();
            checkIfSelected();
        }"
        @blur="checkIfSelected" />
    @foreach ($addressComponents as $addressComponent)
        <x-buk-input name="{{ $name }}-{{ $addressComponent }}" type="hidden" ::value="address.{{ $addressComponent }}" />
    @endforeach
</div>
