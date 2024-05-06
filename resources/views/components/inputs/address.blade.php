@php
    $name = $attributes->get('name');
    //
    // TODO: Consider reimplementing this logic for re-populating the address fields.
    // Alternatively, consider Livewire for this functionality.
    //
    // if (empty($address?->getAttributes()) && !is_null(old($name, request($name)))) {
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
    address: {
        formatted_address: null,
        street_address: null,
        city: null,
        state_name: null,
        state_code: null,
        postal_code: null,
        country_name: null,
        country_code: null,
        latitude: null,
        longitude: null,
    },
    resultsShown: false
}"
    @isset($address) x-init="$nextTick(() => {
    address = {
        formatted_address: {{ Js::from($address->formatted_address) }},
        street_address: {{ Js::from($address->street_address) }},
        city: {{ Js::from($address->city) }},
        state_name: {{ Js::from($address->state?->name) }},
        state_code: {{ Js::from($address->state?->code) }},
        postal_code: {{ Js::from($address->postal_code) }},
        country_name: {{ Js::from($address->country?->name) }},
        country_code: {{ Js::from($address->country?->code) }},
        latitude: {{ Js::from($address->latitude) }},
        longitude: {{ Js::from($address->longitude) }},
    };
});" @endisset
    x-modelable="address" @isset($xModel)
    x-model="{{ $xModel }}" @endisset>
    <x-inputs.input ::value="address.formatted_address" {{ $attributes->merge(['placeholder' => 'Enter an address']) }} autocomplete="off"
        x-data="{
            checkIfSelected() {
                    resultsShown = false;
                    if (!address.formatted_address) {
                        $el.value = '';
                        this.clearAddress();
                        $el.blur();
                    }
                },
                clearAddress() {
                    if (address.formatted_address) Object.keys(address).forEach(key => address[key] = '');
                },
                updateAddress(newAddress) {
                    {{-- Object.keys(address).forEach(key => address[key] = newAddress[key] ?? ''); --}}
                    address.formatted_address = newAddress.formattedAddress;
                    address.street_address = newAddress.number + ' ' + newAddress.street;
                    address.city = newAddress.city;
                    address.state_name = newAddress.state;
                    address.state_code = newAddress.stateCode;
                    address.postal_code = newAddress.postalCode;
                    address.country_name = newAddress.country;
                    address.country_code = newAddress.countryCode;
                    address.latitude = newAddress.latitude;
                    address.longitude = newAddress.longitude;
                }
        }" x-init="Radar.ui.autocomplete({
            container: $el,
            near: {{ Js::from($near) }},
            debounceMS: {{ $debounceMS }},
            minCharacters: {{ $minCharacters }},
            limit: {{ $limit }},
            disabled: {{ Js::from($disabled) }},
            layers: {{ Js::from($layers) }},
            countryCode: {{ Js::from($countryCode) }},
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
        <x-buk-input name="{{ $name }}[{{ $addressComponent }}]" type="hidden" ::value="address.{{ $addressComponent }}" />
    @endforeach
</div>
