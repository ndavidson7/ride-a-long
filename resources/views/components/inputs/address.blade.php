@php
    $name = $attributes->get('name');
    $previousAddress = old($name) ?? request($name);

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
}">
    <x-inputs.input value="{{ $address?->address }}" {{ $attributes }} autocomplete="off" x-data="{
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
    @if (in_array('address', $addressComponents))
        <x-buk-input name="{{ $attributes->get('name') }}-address" type="hidden" maxlength="255" ::value="address.formattedAddress" />
    @endif
    @if (in_array('city', $addressComponents))
        <x-buk-input name="{{ $attributes->get('name') }}-city" type="hidden" ::value="address.city" />
    @endif
    @if (in_array('state', $addressComponents))
        <x-buk-input name="{{ $attributes->get('name') }}-state" type="hidden" ::value="address.state" />
    @endif
    @if (in_array('country', $addressComponents))
        <x-buk-input name="{{ $attributes->get('name') }}-country" type="hidden" ::value="address.country" />
    @endif
    @if (in_array('coordinates', $addressComponents))
        <x-buk-input name="{{ $attributes->get('name') }}-latitude" type="hidden" ::value="address.latitude" />
        <x-buk-input name="{{ $attributes->get('name') }}-longitude" type="hidden" ::value="address.longitude" />
    @endif
</div>
