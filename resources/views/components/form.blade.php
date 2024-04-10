@props(['validated' => false])

@php
    $attributes = $attributes->merge([
        'action' => null,
        'method' => 'POST',
        'hasFiles' => false,
    ]);
@endphp

@if ($validated)
    <x-buk-form {{ $attributes }} x-data="{ valid: false }" x-init="valid = $el.checkValidity()" @input="valid = $el.checkValidity()">
        {{ $slot }}
    </x-buk-form>
@else
    <x-buk-form {{ $attributes }}>
        {{ $slot }}
    </x-buk-form>
@endif
