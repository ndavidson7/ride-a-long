<form method="POST" @isset($action) action="{{ $action }}" @endisset>
    @csrf
    @method($method)

    <x-buttons.button type="submit" {{ $attributes }}>
        {{ $slot }}
    </x-buttons.button>
</form>
