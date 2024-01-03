<input type="email" @class([
    'form-control',
    'form-control-lg',
    'is-invalid' => $errors->has('email'),
]) id="email" name="email" placeholder="" autocomplete="email"
    maxlength="255" pattern="[A-Za-z0-9]+@virginia.edu" value="{{ old('email') }}" required {{ $attributes }} />
<label for="email">UVA email address</label>
