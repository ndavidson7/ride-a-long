<div class="{{ $attributes->get('class') }}">
    <label for="{{ $attributes->get('id') }}" class="form-label">{{ $label }}</label>
    <input @class([
        'form-control',
        'form-control-' . $attributes->get('size') => $attributes->has('size'),
        'is-invalid' => $errors->has($attributes->get('name')),
    ]) value="{{ old($attributes->get('name')) }}"
        @isset($help)aria-describedby="{{ $attributes->get('id') }}-help"@endisset
        {{ $attributes->whereDoesntStartWith('size') }} />
    @isset($help)
        <div id="{{ $attributes->get('id') }}-help" class="form-text">{{ $help }}</div>
    @endisset
    <div class="invalid-feedback">
        @error($attributes->get('name'))
            {{ $message }}
        @enderror
    </div>
</div>
