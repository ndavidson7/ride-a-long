@props(['user'])

<div class="flex items-center gap-4">
    <div class="size-16 flex-shrink-0 rounded-full shadow-md"
        @if (session('uploadedPfp', false)) x-init="
                    Echo.private(`profile-pictures.${window.userId}`).listen(
                        'ProfilePictureUploaded', (e) => {
                            $el.innerHTML = `<img class='size-full rounded-full' src='${e.url}'
                                alt='{{ $user->name }}'s profile picture'>`;
                        }
                    );
                " @endif>

        @if (session('uploadedPfp', false))
            <div class='size-full animate-pulse rounded-full bg-gray-300' role='status'>
                <span class='sr-only'>Loading...</span>
            </div>
        @else
            <x-pfp class="size-full" :$user />
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
<div class="mt-3 space-y-3">

    <h1 class="text-wrap text-left text-lg/none font-medium">{{ $user->name }}</h1>

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
