<div>
    {{-- ID needed for inputs that supply information to map.js MapComponent --}}
    <mapbox-address-autofill access-token="{{ config('mapbox.token') }}" x-init="$el.options = {
        country: '{{ $country }}',
        limit: {{ $limit }},
        proximity: 'ip',
        streets: false
    };
    
    $el.addEventListener('retrieve', (event) => {
        const featureCollection = event.detail;
        const inputEl = event.target;
        console.log(featureCollection);
    });">
        <x-inputs.input autocomplete="address-level2" {{ $attributes }} />
    </mapbox-address-autofill>
    {{-- <input type="hidden" class="address" id="{{ $attributes->get('id') }}-address"
        name="{{ $attributes->get('name') }}-address" maxlength="255" value="{{ $address?->address }}" />
    <input type="hidden" class="city" name="{{ $attributes->get('name') }}-city" value="{{ $address?->city }}" />
    <input type="hidden" class="state" name="{{ $attributes->get('name') }}-state" value="{{ $address?->state }}" />
    <input type="hidden" class="country" name="{{ $attributes->get('name') }}-country"
        value="{{ $address?->country }}" />
    <input type="hidden" class="latitude" id="{{ $attributes->get('id') }}-latitude"
        name="{{ $attributes->get('name') }}-latitude" value="{{ $address?->latitude }}" />
    <input type="hidden" class="longitude" id="{{ $attributes->get('id') }}-longitude"
        name="{{ $attributes->get('name') }}-longitude" value="{{ $address?->longitude }}" /> --}}
</div>
