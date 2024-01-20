@php
    $attributes = $attributes->class(['px-3', 'py-2', 'min-h-10' => $size === 'sm', 'min-h-12' => $size === 'md', 'min-h-14' => $size === 'lg', 'inline-flex', 'items-center', 'justify-center', 'whitespace-nowrap', 'border-2', 'rounded-md', 'text-sm' => $size === 'sm', 'text-base' => $size === 'md', 'text-lg' => $size === 'lg', 'font-medium', 'transition-colors', 'focus-visible:outline-none', 'focus-visible:ring-1', 'disabled:pointer-events-none', 'disabled:opacity-50'])->merge(['type' => 'submit']);
@endphp

@if ($withValidation)
    <button {{ $attributes }} ::disabled="!valid">
        {{ $slot }}
    </button>
@else
    <button {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
