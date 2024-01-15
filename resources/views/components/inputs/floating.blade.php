<div class="{{ $attributes->merge(['class' => 'form-floating'])->get('class') }}">
    <input @class([
        'form-control',
        'is-invalid' => $errors->has($attributes->get('name')),
    ]) placeholder="" value="{{ old($attributes->get('name')) }}"
        @isset($help)aria-describedby="{{ $attributes->get('id') }}-help"@endisset
        {{ $attributes->whereDoesntStartWith('size') }} />
    <label for="{{ $attributes->get('id') }}">{{ $label }}</label>
    @isset($help)
        <div id="{{ $attributes->get('id') }}-help" class="form-text">{{ $help }}</div>
    @endisset
    <div class="invalid-feedback">
        @error($attributes->get('name'))
            {{ $message }}
        @enderror
    </div>
</div>
