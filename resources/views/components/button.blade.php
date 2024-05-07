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
            'bg-green-500' => $variant === 'success',
            'bg-red-500' => $variant === 'danger',
            'bg-yellow-500' => $variant === 'warning',
            'text-black' => $variant !== 'plain',
            'border',
            'border-gray-400',
            'ring-2',
            'ring-offset-2',
            'ring-transparent',
            'rounded-full',
            'text-sm' => $size === 'sm',
            'text-base' => $size === 'md',
            'text-lg' => $size === 'lg',
            // 'font-medium',
            'font-semibold',
            'transition',
            'focus-visible:outline-none',
            'focus-visible:border-transparent',
            'focus-visible:ring-blue-300' => $variant === 'primary',
            'focus-visible:ring-green-300' => $variant === 'success',
            'focus-visible:ring-red-300' => $variant === 'danger',
            'focus-visible:ring-yellow-300' => $variant === 'warning',
            'active:bg-blue-600' => $variant === 'primary',
            'active:bg-green-600' => $variant === 'success',
            'active:bg-red-600' => $variant === 'danger',
            'active:bg-yellow-600' => $variant === 'warning',
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
