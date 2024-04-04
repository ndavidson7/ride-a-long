@props(['name', 'rows' => '3', 'validated' => false, 'styled' => true])

@php
    if ($styled) {
        $attributes = $attributes->class([
            'w-full',
            'rounded-md',
            'border',
            'border-gray-400',
            'px-3',
            'py-1',
            'ring-2',
            'ring-transparent',
            'transition-all',
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
    <div x-data="{ error: '@error($name){{ $message }}@enderror', validate(el) { this.error = !el.checkValidity() ? el.validationMessage : '' } }">
        <x-buk-textarea :$name :$rows {{ $attributes }} @blur="validate($el)" ::class="error && '!border-transparent !ring-red-600'"
            @input="if (error) validate($el)" />
        <p class="min-h-[1lh] text-sm font-light text-red-600" x-show="error" x-text="error">
        </p>
    </div>
@else
    <x-buk-textarea :$name :$rows {{ $attributes }} />
@endif
