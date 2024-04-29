<form class="mx-auto max-w-xl space-y-3">
    <div class="space-y-1">
        <x-buk-label class="inline-block space-y-1 font-medium" for="pfp">
            <span>Profile picture</span>
            <div class="size-32 group relative cursor-pointer rounded-full shadow-md">
                @if ($pfp = $user->fetchFirstMedia())
                    <img class="size-full rounded-full transition-opacity group-hover:opacity-30"
                        src="{{ $pfp['file_url'] }}" alt="{{ $user->name }}'s profile picture">
                @else
                    <x-fas-circle-user class="size-full rounded-full bg-white text-gray-400" />
                @endif
                <div
                    class="absolute inset-0 grid place-items-center rounded-full bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                    <x-fas-camera class="size-6 text-white" />
                </div>
            </div>
        </x-buk-label>
        <x-inputs.input class="!min-h-0 !p-0" id="pfp" name="pfp" type="file"
            aria-describedby="pfp-constraints" accept="image/*" />
        <p class="text-xs text-gray-600" id="pfp-constraints">Max file size 2MB. Photo will be automatically
            cropped
            towards detected face and rounded.</p>
    </div>

    {{-- <h1 class="text-wrap text-left text-lg/none font-medium">{{ $user->name }}</h1> --}}

    <div>
        <x-buk-label class="font-medium" for="bio">Bio</x-buk-label>
        <x-inputs.textarea name="bio" aria-describedby="bio-limit" rows=4 maxlength="255" autofocus
            validated></x-inputs.textarea>
        <p class="text-xs/none text-gray-600" id="bio-limit" x-text="`${$wire.bio?.length ?? '0'}/255 characters`">
        </p>
    </div>

    <x-button size="sm">Save</x-button>
</form>
