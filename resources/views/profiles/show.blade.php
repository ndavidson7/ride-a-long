<x-layouts.app class="mx-auto max-w-lg" title="{{ $user->name }}'s Profile">
    <div class="flex items-center gap-4">
        <div class="size-16 flex-shrink-0 rounded-full shadow-md"
            @if ($uploadedPfp) x-init="
                    Echo.private(`profile-pictures.${window.userId}`).listen(
                        'ProfilePictureUploaded', (e) => {
                            console.log('pfp uploaded event:', e);
                            $el.innerHTML = `<img class='size-full rounded-full' src='${e.url}'
                                alt='{{ $user->name }}'s profile picture'>`;
                        }
                    );
                " @endif>

            @if ($uploadedPfp)
                <div class='size-full animate-pulse rounded-full bg-gray-300' role='status'>
                    <span class='sr-only'>Loading...</span>
                </div>
            @elseif ($pfp = $user->fetchFirstMedia())
                <img class="size-full rounded-full" src="{{ $pfp['file_url'] }}"
                    alt="{{ $user->name }}'s profile picture">
            @else
                <x-fas-circle-user class="size-full rounded-full bg-white text-gray-400" />
            @endif

        </div>
        <div class="space-y-2">
            <p class="text-xl/none font-medium">5 <span class="text-gray-500">trips</span></p>
            <div class="flex items-center gap-1">
                <p class="text-xl/none font-medium">78%</p>
                <x-fas-thumbs-up class="size-5 text-gray-500" />
            </div>
        </div>
    </div>
    <div class="mt-4 space-y-3">
        <h1 class="text-wrap text-lg/none font-medium">{{ $user->name }}</h1>

        @if ($user->id == auth()->id())
            <x-buttons.anchor href="{{ route('users.edit') }}" size="sm">Edit profile</x-buttons.anchor>
        @endif

        <p class="text-pretty break-words text-sm text-gray-800">{{ $user->bio }}</p>
        <div class="flex flex-wrap items-center gap-1">
            @if ($user->college_id)
                <x-pill class="gap-1 bg-blue-300 px-2.5 py-1 text-xs font-semibold">
                    <x-fas-graduation-cap class="size-3" /> Student
                </x-pill>
            @endif
            @if ($user->is_driver)
                <x-pill class="gap-1 bg-green-300 px-2.5 py-1 text-xs font-semibold">
                    <x-fas-car class="size-3" /> Driver
                </x-pill>
            @endif
        </div>
    </div>

    {{-- <h5 class="card-title">Year</h5>
    <h6 class="card-subtitle mb-5">{{ $user->year_formatted }}</h6>
    <h5 class="card-title">Major</h5>
    <h6 class="card-subtitle mb-5">{{ $user->major?->name }}</h6> --}}
</x-layouts.app>
