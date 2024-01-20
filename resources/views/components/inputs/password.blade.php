<x-inputs.input type="password" minlength="8" maxlength="255"
    {{ $attributes->merge(['name' => 'password', 'placeholder' => 'Password', 'autocomplete' => 'current-password']) }} />
