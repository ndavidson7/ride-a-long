<div class="{{ $attributes->merge(['class' => 'autocomplete'])->get('class') }}">
    {{-- ID needed for inputs that supply information to map.js MapComponent --}}
    <label for="{{ $attributes->get('id') }}" class="form-label">{{ $label }}</label>
    <input type="text" @class([
        'place',
        'form-control',
        'form-control-' . $attributes->get('size') => $attributes->has('size'),
        // 'is-invalid' => $errors->has($attributes->get('name') . '-address'),
    ])
        @isset($help)aria-describedby="{{ $attributes->get('id') }}-help"@endisset
        value="{{ $address?->address }}" {{ $attributes }} />
    @isset($help)
        <div id="{{ $attributes->get('id') }}-help" class="form-text">{{ $help }}</div>
    @endisset
    <input type="hidden" class="address" id="{{ $attributes->get('id') }}-address"
        name="{{ $attributes->get('name') }}-address" maxlength="255" value="{{ $address?->address }}" />
    <input type="hidden" class="city" name="{{ $attributes->get('name') }}-city" value="{{ $address?->city }}" />
    <input type="hidden" class="state" name="{{ $attributes->get('name') }}-state" value="{{ $address?->state }}" />
    <input type="hidden" class="country" name="{{ $attributes->get('name') }}-country"
        value="{{ $address?->country }}" />
    <input type="hidden" class="latitude" id="{{ $attributes->get('id') }}-latitude"
        name="{{ $attributes->get('name') }}-latitude" value="{{ $address?->latitude }}" />
    <input type="hidden" class="longitude" id="{{ $attributes->get('id') }}-longitude"
        name="{{ $attributes->get('name') }}-longitude" value="{{ $address?->longitude }}" />
</div>
