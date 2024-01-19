<div class="relative">
    <x-dynamic-component :$component {{ $attributes->merge(['class' => 'peer pt-6']) }} />
    <x-buk-label
        class="absolute left-3 top-1.5 text-xs text-gray-600 transition-all peer-placeholder-shown:top-6 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-gray-600"
        for="{{ $attributes->get('id') ?? $attributes->get('name') }}">
        {{ $slot }}
    </x-buk-label>
</div>
