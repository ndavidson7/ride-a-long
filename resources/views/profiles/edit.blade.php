<x-layouts.app class="mx-auto max-w-xl" title="Edit profile" :$entries>
    <x-form class="space-y-3" action="{{ route('users.update') }}" method="put" :hasFiles="true">

        <div class="space-y-1.5" x-data="{
            pfpUrl: '{{ $user->fetchFirstMedia()['file_url'] ?? '' }}',
            error: '{{ $errors->first('pfp') }}',
        }">
            <x-buk-label class="block max-w-fit space-y-1 font-medium" for="pfp">
                <span>Profile picture</span>
                <div class="size-32 group/pfp relative cursor-pointer rounded-full shadow-md">
                    <template x-if="pfpUrl">
                        <img class="size-full rounded-full object-cover transition-opacity group-hover/pfp:opacity-30"
                            alt="Your profile picture" :src="pfpUrl">
                    </template>
                    <x-fas-circle-user class="size-full rounded-full bg-white text-gray-400" x-show="!pfpUrl" x-cloak />
                    <div
                        class="absolute inset-0 grid place-items-center rounded-full bg-black/50 opacity-0 transition-opacity group-hover/pfp:opacity-100">
                        <x-fas-camera class="size-6 text-white" />
                    </div>
                </div>
            </x-buk-label>
            <div x-data>
                <input class="hidden" id="pfp" name="pfp" type="file" aria-describedby="pfp-constraints"
                    accept="image/*" x-ref="pfpInput"
                    @change="
                        const file = $event.target.files[0];
                        if (!file) return;
                        if (file.size > 2 * 1024 * 1024) {
                            $event.target.value = null;
                            error = 'File size exceeds 2MB.';
                            return;
                        }
                        pfpUrl = URL.createObjectURL(file);
                        $refs.deletePfpInput.checked = false;
                        error = '';
                        await $nextTick();
                        URL.revokeObjectURL(pfpUrl);
                    " />
                <x-button class="bg-white" type="button" ::class="error && 'ring-1 ring-red-600'" @click="$refs.pfpInput.click()"
                    size="sm">Change</x-button>

                <input class="hidden" name="delete-pfp" type="checkbox" value="1" x-ref="deletePfpInput" />
                <x-button class="bg-white" type="button" size="sm"
                    @click="
                        $refs.deletePfpInput.checked = true;
                        pfpUrl = '';
                        $refs.pfpInput.value = null;
                    "
                    ::disabled="!pfpUrl">Remove</x-button>
            </div>
            <p class="text-xs text-gray-600" id="pfp-constraints">Max file size 2MB. Photo will be automatically
                cropped towards detected face and rounded.</p>
            <p class="text-sm text-red-600" x-show="error" x-text="error"></p>
        </div>

        <div x-data="{ bio: '{{ $user->bio }}' }">
            <x-buk-label class="font-medium" for="bio">Bio</x-buk-label>
            <x-inputs.textarea name="bio" aria-describedby="bio-limit" rows=4 maxlength="255" autofocus validated
                x-model="bio"></x-inputs.textarea>
            <p class="text-xs/none text-gray-600" id="bio-limit" x-text="`${bio.length}/255 characters`">
            </p>
        </div>

        <x-button class="bg-white" size="sm">Save</x-button>
        <x-button class="bg-white text-red-500" href="{{ route('users.show') }}" as="anchor"
            size="sm">Cancel</x-button>

        {{-- <div class="row mb-3">
            <div class="col-sm-6 mb-sm-0 mb-3">
                <label class="form-label" for="year">Year</label>
                <select class="form-select" id="year" name="year">
                    <option value="" @selected(!$user->year)>Select...</option>
                    <option value="1" @selected($user->year == 1)>First</option>
                    <option value="2" @selected($user->year == 2)>Second</option>
                    <option value="3" @selected($user->year == 3)>Third</option>
                    <option value="4" @selected($user->year == 4)>Fourth</option>
                    <option value="5" @selected($user->year == 5)>Graduate/Further
                        Studies</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label class="form-label" for="major">Major</label>
                <select class="form-select" id="major" name="major">
                    <option value="" @selected(!$user->major_id)>Select...</option>
                    @foreach ($majors as $major)
                        <option value="{{ $major->id }}" @selected($user->major_id == $major->id)>
                            {{ $major->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3>Car Info</h3>
        <div class="row mb-3">
            <div class="col-sm-4 mb-sm-0 mb-3">
                <label class="form-label" for="car-license-plate">License Plate</label>
                <input class="form-control" id="car-license-plate" name="car-license-plate" type="text"
                    value="{{ $car->license_plate }}" aria-describedby="plate-limit" maxlength="7" />
                <div class="form-text" id="plate-limit">Max 7 characters.</div>
            </div>
            <div class="col-sm-4 mb-sm-0 mb-3">
                <label class="form-label" for="car-make">Make</label>
                <input class="form-control" id="car-make" name="car-make" type="text" value="{{ $car->make }}"
                    aria-describedby="make-limit" maxlength="63" />
                <div class="form-text" id="make-limit">Max 63 characters.</div>
            </div>
            <div class="col-sm-4">
                <label class="form-label" for="car-color">Color</label>
                <input class="form-control" id="car-color" name="car-color" type="text" value="{{ $car->color }}"
                    aria-describedby="color-limit" maxlength="63" />
                <div class="form-text" id="color-limit">Max 63 characters.</div>
            </div>
        </div>
        <div class="d-flex">
            <button class="btn btn-primary me-2" type="submit" disabled>Save</button>
            <a class="btn btn-danger" href="{{ route('users.destroy') }}"
                onclick="return confirm('Are you sure you want to delete your account? This action is irreversible.');">Delete
                Account</a>
        </div> --}}
    </x-form>
</x-layouts.app>
