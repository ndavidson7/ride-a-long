<x-inputs.input type="email" autocomplete="email" maxlength="255"
    {{ $attributes->merge(['name' => 'email', 'pattern' => '[A-Za-z0-9]+@virginia.edu', 'placeholder' => 'Email address']) }} />
