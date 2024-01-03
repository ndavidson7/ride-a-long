<div class="{{ $attributes->merge(['class' => 'form-floating'])->get('class') }}">
    <input type="email" @class([
        'form-control',
        'form-control-lg',
        'is-invalid' => $errors->has('email'),
    ]) id="email" name="email" placeholder="" autocomplete="email"
        maxlength="255" pattern="[A-Za-z0-9]+@virginia.edu" value="{{ old('email') }}" required
        @if ($attributes->has('with-help')) aria-describedby="email-help" @endif />
    <label for="email">UVA email address</label>
    @if ($attributes->has('with-help'))
        <div class="form-text" id="email-help">Valid UVA email</div>
    @endif
    <div class="invalid-feedback">
        @error('email')
            {{ $message }}
        @enderror
    </div>
</div>
