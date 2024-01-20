<x-layouts.app title="Edit profile" :$entries>
    <main>
        <form class="disabled-until-change col-11 col-sm-10 col-md-9 col-lg-8 col-xl-7 col-xxl-6 container py-4"
            action="{{ route('users.update') }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <h3>Profile</h3>
            <div class="row align-items-center mb-3">
                <label class="col-md-auto col-form-label" id="pfp-label" for="pfp"
                    title="Current profile picture if exists">
                    @if ($pfp = $user->fetchFirstMedia())
                        <img class="rounded-circle shadow-lg" src="{{ $pfp['file_url'] }}">
                    @else
                        <i class="bi bi-person-circle" style="font-size: 200px"></i>
                    @endif
                </label>
                <div class="col">
                    <input class="form-control @error('pfp') is-invalid @enderror" id="pfp" name="pfp"
                        type="file" aria-describedby="pfp-constraints" accept="image/*">
                    <div class="form-text" id="pfp-constraints">Max file size 2MB. Photo will be automatically cropped
                        towards detected face and rounded.</div>
                    <div class="invalid-feedback" id="pfp-invalid-feedback">
                        @error('pfp')
                            {{ $message }}
                        @enderror
                    </div>
                    @if ($pfp)
                        <button class="btn btn-danger mt-2" id="delete-pfp-button" type="button">Delete profile
                            picture</button>
                        <input id="delete-pfp" name="delete-pfp" type="hidden">
                    @endif
                </div>
            </div>
            <div class="row mb-3">
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
            <div class="mb-3">
                <label class="form-label" for="bio">Bio</label>
                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio"
                    aria-describedby="bio-limit" rows=4 maxlength="255">{{ $user->bio }}</textarea>
                <div class="form-text" id="bio-limit">Max 255 characters.</div>
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
                    <input class="form-control" id="car-make" name="car-make" type="text"
                        value="{{ $car->make }}" aria-describedby="make-limit" maxlength="63" />
                    <div class="form-text" id="make-limit">Max 63 characters.</div>
                </div>
                <div class="col-sm-4">
                    <label class="form-label" for="car-color">Color</label>
                    <input class="form-control" id="car-color" name="car-color" type="text"
                        value="{{ $car->color }}" aria-describedby="color-limit" maxlength="63" />
                    <div class="form-text" id="color-limit">Max 63 characters.</div>
                </div>
            </div>
            <div class="d-flex">
                <button class="btn btn-primary me-2" type="submit" disabled>Save</button>
                <a class="btn btn-danger" href="{{ route('users.destroy') }}"
                    onclick="return confirm('Are you sure you want to delete your account? This action is irreversible.');">Delete
                    Account</a>
            </div>
        </form>
    </main>
</x-layouts.app>
