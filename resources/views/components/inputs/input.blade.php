{{-- <div class="{{ $attributes->get('class') }}">
    <label for="{{ $attributes->get('id') }}" class="form-label">{{ $label }}</label>
    <x-buk-input :$type  />
    @isset($help)
        <div id="{{ $attributes->get('id') }}-help" class="form-text">{{ $help }}</div>
    @endisset
    <div class="invalid-feedback">
        @error($attributes->get('name'))
            {{ $message }}
        @enderror
    </div>
</div> --}}

<x-buk-input
    {{ $attributes->class([
        'flex',
        'min-h-10' => $size === 'sm',
        'min-h-12' => $size === 'md',
        'min-h-14' => $size === 'lg',
        'w-full',
        'rounded-md',
        'border',
        'px-3',
        'py-1',
        'text-sm' => $size === 'sm',
        'text-base' => $size === 'md',
        'text-lg' => $size === 'lg',
        'file:border-0',
        'file:bg-transparent',
        'file:font-medium',
        'placeholder:text-gray-500',
        'focus-visible:outline-none',
        'focus-visible:ring-2',
        'focus-visible:ring-offset-2',
        'disabled:cursor-not-allowed',
        'disabled:opacity-50',
    ]) }} />
