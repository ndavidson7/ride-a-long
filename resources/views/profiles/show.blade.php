<x-layouts.app class="mx-auto max-w-lg space-y-3" title="{{ $user->name }}'s Profile">
    @if ($user->id == auth()->id())
        <x-button href="{{ route('users.edit') }}" as="anchor" size="sm">Edit profile</x-button>
    @endif
    <x-cards.user :$user />

    {{-- <h5 class="card-title">Year</h5>
    <h6 class="card-subtitle mb-5">{{ $user->year_formatted }}</h6>
    <h5 class="card-title">Major</h5>
    <h6 class="card-subtitle mb-5">{{ $user->major?->name }}</h6> --}}
</x-layouts.app>
