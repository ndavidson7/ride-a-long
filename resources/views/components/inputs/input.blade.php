@props(['size' => 'md', 'validated' => false, 'unstyled' => false])

@php
    if ($unstyled === false) {
        $attributes = $attributes->class([
            'min-h-10' => $size === 'sm',
            'min-h-12' => $size === 'md',
            'min-h-14' => $size === 'lg',
            'w-full',
            'rounded-md',
            'border',
            'border-gray-400',
            'px-3',
            'py-1',
            'ring-2',
            'ring-transparent',
            'text-sm' => $size === 'sm',
            'text-base' => $size === 'md',
            'text-lg' => $size === 'lg',
            'cursor-pointer' => $attributes->get('type') === 'file',
            'transition-all',
            'file:cursor-pointer',
            'file:bg-gray-100',
            'file:border-0',
            'file:me-4',
            'file:py-2',
            'file:px-4',
            'file:font-medium',
            'placeholder:text-gray-500',
            'focus:outline-none',
            'focus:border-blue-600',
            'focus:ring-blue-200',
            'disabled:cursor-not-allowed',
            'disabled:opacity-50',
        ]);
    }
@endphp

{{-- Not the cleanest but the only way I could make this work --}}
@if ($validated)
    <div x-data="{ error: '@error($attributes->get('name')){{ $message }}@enderror', validate(el) { this.error = !el.checkValidity() ? el.validationMessage : '' } }">
        <x-buk-input {{ $attributes }} @blur="validate($el)" ::class="error && '!border-transparent !ring-red-600'" @input="if (error) validate($el)" />
        <p class="mx-2 mt-1 min-h-[1lh] text-sm font-light text-red-600" :class="error ? 'visible' : 'invisible'"
            x-text="error"></p>
    </div>
@else
    <x-buk-input {{ $attributes }} />
@endif
