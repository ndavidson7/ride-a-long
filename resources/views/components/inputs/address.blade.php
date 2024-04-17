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
        formattedAddress: null,
        streetAddress: null,
        city: null,
        state: null,
        stateCode: null,
        postalCode: null,
        country: null,
        countryCode: null,
        latitude: null,
        longitude: null,
    },
    resultsShown: false
}"
    @isset($address) x-init="$nextTick(() => {
    address = {
        formattedAddress: {{ Js::from($address->formatted_address) }},
        streetAddress: {{ Js::from($address->street_address) }},
        city: {{ Js::from($address->city) }},
        state: {{ Js::from($address->state?->name) }},
        stateCode: {{ Js::from($address->state?->code) }},
        postalCode: {{ Js::from($address->postal_code) }},
        country: {{ Js::from($address->country?->name) }},
        countryCode: {{ Js::from($address->country?->code) }},
        latitude: {{ Js::from($address->latitude) }},
        longitude: {{ Js::from($address->longitude) }},
    };
});" @endisset
    x-modelable="address" @isset($xModel)
    x-model="{{ $xModel }}" @endisset>
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
                {{-- Object.keys(address).forEach(key => address[key] = newAddress[key] ?? ''); --}}
                address.formattedAddress = newAddress.formattedAddress;
                address.streetAddress = newAddress.number + ' ' + newAddress.street;
                address.city = newAddress.city;
                address.state = newAddress.state;
                address.stateCode = newAddress.stateCode;
                address.postalCode = newAddress.postalCode;
                address.country = newAddress.country;
                address.countryCode = newAddress.countryCode;
                address.latitude = newAddress.latitude;
                address.longitude = newAddress.longitude;
            }
    }"
        x-init="Radar.ui.autocomplete({
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
        <x-buk-input name="{{ $name }}-{{ $addressComponent }}" type="hidden"
            x-model="address.{{ $addressComponent }}" />
    @endforeach
</div>
