<div class="{{ $attributes->merge(['class' => 'form-floating'])->get('class') }}">
    <input type="{{ $attributes->get('type') }}" @class([
        'form-control',
        'form-control-lg',
        'is-invalid' => $errors->has($attributes->get('name')),
    ]) placeholder=""
        value="{{ old($attributes->get('name')) }}" {{ $attributes }} />
    <label for="{{ $attributes->get('id') }}">{{ $slot }}</label>
    <div class="invalid-feedback">
        @error($attributes->get('name'))
            {{ $message }}
        @enderror
    </div>
</div>
