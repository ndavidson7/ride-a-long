@props([
    'size' => 'md',
    'validated' => false,
    'unstyled' => false,
    'as' => 'button',
    'method' => 'POST',
    'action' => null,
    'variant' => 'primary',
])

{{-- blade-formatter-disable --}}
@php
    if ($unstyled === false)
    {
        $attributes = $attributes->class([
            'px-3' => $size === 'sm',
            'py-2' => $size === 'sm',
            'px-3.5' => $size === 'md',
            'py-2.5' => $size === 'md',
            'px-4' => $size === 'lg',
            'py-3' => $size === 'lg',
            'min-h-10' => $size === 'sm',
            'min-h-11' => $size === 'md',
            'min-h-12' => $size === 'lg',
            'inline-flex',
            'items-center',
            'justify-center',
            'whitespace-nowrap',
            'bg-blue-500' => $variant === 'primary',
            'bg-red-500' => $variant === 'danger',
            'text-white' => $variant !== 'plain',
            'border',
            'border-gray-400',
            'ring-2',
            'ring-transparent',
            'rounded-md',
            'text-sm' => $size === 'sm',
            'text-base' => $size === 'md',
            'text-lg' => $size === 'lg',
            'font-medium',
            'transition-all',
            'focus:outline-none',
            'focus:border-blue-700' => $variant === 'primary',
            'focus:ring-blue-200' => $variant === 'primary',
            'focus:border-red-700' => $variant === 'danger',
            'focus:ring-red-200' => $variant === 'danger',
            'active:bg-blue-600' => $variant === 'primary',
            'active:bg-red-600' => $variant === 'danger',
            'disabled:pointer-events-none' => $as === 'anchor',
            'disabled:cursor-not-allowed' => $as !== 'anchor',
            'disabled:opacity-50',
        ]);
    }
@endphp

@switch($as)
    @case('anchor')
        <a {{ $attributes }}>
            {{ $slot }}
        </a>
        @break

    @case('form')
        <x-form :$method :$action>
            <button {{ $attributes }} type="submit">
                {{ $slot }}
            </button>
        </x-form>
        @break

    @case('button')
    @default
        @if ($validated)
            <button {{ $attributes->merge(['type' => 'submit']) }} :disabled="!valid">
                {{ $slot }}
            </button>
        @else
            <button {{ $attributes->merge(['type' => 'submit']) }}>
                {{ $slot }}
            </button>
        @endif
        @break
@endswitch
{{-- blade-formatter-enable --}}
