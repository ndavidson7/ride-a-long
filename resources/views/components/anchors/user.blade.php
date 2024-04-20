@props(['user', 'size' => 'md'])

<x-cards.hover>
    <x-slot:anchor class="inline-flex items-center gap-1 align-middle hover:underline"
        href="{{ route('users.show', $user) }}">
        <x-pfp @class([
            'size-8' => $size === 'sm',
            'size-12' => $size === 'md',
            'size-16' => $size === 'lg',
            'shadow-lg',
        ]) :$user />
        <div>{{ $user->name }}</div>
    </x-slot:anchor>

    <x-slot:card class="max-w-64 rounded-xl border bg-white p-3 shadow-md">
        <x-cards.user :$user />
    </x-slot:card>
</x-cards.hover>
