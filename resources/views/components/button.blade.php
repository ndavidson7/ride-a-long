@props([
    'size' => 'md',
    'withValidation' => false,
    'withoutStyles' => false,
    'as' => 'button',
    'method' => 'POST',
    'action' => null,
])

{{-- blade-formatter-disable --}}
@php
    if (!$withoutStyles)
    {
        $attributes = $attributes->class([
            'px-3',
            'py-2',
            'min-h-10' => $size === 'sm',
            'min-h-12' => $size === 'md',
            'min-h-14' => $size === 'lg',
            'inline-flex',
            'items-center',
            'justify-center',
            'whitespace-nowrap',
            'border-2',
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
            'focus:border-blue-600',
            'focus:ring-blue-200',
            'disabled:pointer-events-none',
            'disabled:opacity-50'
        ]);
    }

    $attributes = $attributes->merge(['type' => 'submit']);
@endphp

@switch($as)
    @case('anchor')
        <a {{ $attributes }}>
            {{ $slot }}
        </a>
        @break

    @case('form')
        <x-form :$method :$action>
            <x-button type="submit" {{ $attributes }}>
                {{ $slot }}
            </x-button>
        </x-form>
        @break

    @case('button')
    @default
        @if ($withValidation)
            <button {{ $attributes }} :disabled="!valid">
                {{ $slot }}
            </button>
        @else
            <button {{ $attributes }}>
                {{ $slot }}
            </button>
        @endif
        @break
@endswitch
{{-- blade-formatter-enable --}}
