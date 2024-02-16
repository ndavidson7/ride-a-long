<div x-id="['autocomplete']" x-data="{
    onSelection: (address) => {
        $refs.address.value = address.formattedAddress;
        $refs.city.value = address.city;
        $refs.state.value = address.state;
        $refs.country.value = address.country;
        $refs.latitude.value = address.latitude;
        $refs.longitude.value = address.longitude;
    }
}">
    <x-inputs.input value="{{ $address?->address }}" {{ $attributes }} autocomplete="off" ::id="$id('autocomplete')"
        x-init="Radar.ui.autocomplete({
            container: $id('autocomplete'),
            near: {{ $near ?? 'null' }},
            debounceMS: {{ $debounceMS }},
            minCharacters: {{ $minCharacters }},
            limit: {{ $limit }},
            placeholder: '{{ $placeholder }}',
            disabled: {{ $disabled ? 'true' : 'false' }},
            layers: {{ Js::from($layers) }},
            countryCode: '{{ $countryCode }}',
            onSelection: onSelection,
            onError: (error) => {
                console.error(error);
            }
        })" />
    <input name="{{ $attributes->get('name') }}-address" type="hidden" value="{{ $address?->address }}" maxlength="255"
        x-ref="address" />
    <input name="{{ $attributes->get('name') }}-city" type="hidden" value="{{ $address?->city }}" x-ref="city" />
    <input name="{{ $attributes->get('name') }}-state" type="hidden" value="{{ $address?->state }}" x-ref="state" />
    <input name="{{ $attributes->get('name') }}-country" type="hidden" value="{{ $address?->country }}"
        x-ref="country" />
    <input name="{{ $attributes->get('name') }}-latitude" type="hidden" value="{{ $address?->latitude }}"
        x-ref="latitude" />
    <input name="{{ $attributes->get('name') }}-longitude" type="hidden" value="{{ $address?->longitude }}"
        x-ref="longitude" />
</div>
