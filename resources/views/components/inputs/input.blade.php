@php
    $attributes = $attributes->class(['flex', 'min-h-10' => $size === 'sm', 'min-h-12' => $size === 'md', 'min-h-14' => $size === 'lg', 'w-full', 'rounded-md', 'border', 'border-gray-400', 'px-3', 'py-1', 'ring-2', 'ring-offset-2', 'ring-transparent', 'text-sm' => $size === 'sm', 'text-base' => $size === 'md', 'text-lg' => $size === 'lg', 'file:border-0', 'file:bg-transparent', 'file:font-medium', 'placeholder:text-gray-500', 'focus:outline-none', 'focus:ring-blue-600', 'disabled:cursor-not-allowed', 'disabled:opacity-50']);
@endphp

{{-- Not the cleanest but the only way I could make this work --}}
@if ($withValidation)
    <div x-data="{ error: '@error($attributes->get('name')) {{ $message }} @enderror', validate(el) { this.error = !el.checkValidity() ? el.validationMessage : '' } }">
        <x-buk-input {{ $attributes }} @blur="validate($el)" ::class="error && '!ring-red-600'" @input="if (error) validate($el)" />
        <div class="mt-2 text-red-600" x-cloak x-show="error" x-text="error"></div>
    </div>
@else
    <x-buk-input {{ $attributes }} />
@endif
