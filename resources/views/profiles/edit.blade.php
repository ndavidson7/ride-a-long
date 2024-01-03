<x-layouts.main title="Edit profile" :$entries>
    <main>
        <form class="disabled-until-change container py-4 col-11 col-sm-10 col-md-9 col-lg-8 col-xl-7 col-xxl-6"
            action="{{ route('users.update') }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <h3>Profile</h3>
            <div class="row mb-3 align-items-center">
                <label for="pfp" id="pfp-label" class="col-md-auto col-form-label"
                    title="Current profile picture if exists">
                    @if ($pfp = $user->fetchFirstMedia())
                        <img src="{{ $pfp['file_url'] }}" class="rounded-circle shadow-lg">
                    @else
                        <i class="bi bi-person-circle" style="font-size: 200px"></i>
                    @endif
                </label>
                <div class="col">
                    <input type="file" class="form-control @error('pfp') is-invalid @enderror" id="pfp"
                        name="pfp" accept="image/*" aria-describedby="pfp-constraints">
                    <div id="pfp-constraints" class="form-text">Max file size 2MB. Photo will be automatically cropped
                        towards detected face and rounded.</div>
                    <div class="invalid-feedback" id="pfp-invalid-feedback">
                        @error('pfp')
                            {{ $message }}
                        @enderror
                    </div>
                    @if ($pfp)
                        <button type="button" id="delete-pfp-button" class="btn btn-danger mt-2">Delete profile
                            picture</button>
                        <input type="hidden" name="delete-pfp" id="delete-pfp">
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-6 mb-3 mb-sm-0">
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
                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows=4 maxlength="255"
                    aria-describedby="bio-limit">{{ $user->bio }}</textarea>
                <div id="bio-limit" class="form-text">Max 255 characters.</div>
            </div>

            <h3>Car Info</h3>
            <div class="row mb-3">
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <label class="form-label" for="car-license-plate">License Plate</label>
                    <input type="text" class="form-control" id="car-license-plate" name="car-license-plate"
                        maxlength="7" aria-describedby="plate-limit" value="{{ $car->license_plate }}" />
                    <div id="plate-limit" class="form-text">Max 7 characters.</div>
                </div>
                <div class="col-sm-4 mb-3 mb-sm-0">
                    <label class="form-label" for="car-make">Make</label>
                    <input type="text" class="form-control" id="car-make" name="car-make" maxlength="63"
                        aria-describedby="make-limit" value="{{ $car->make }}" />
                    <div id="make-limit" class="form-text">Max 63 characters.</div>
                </div>
                <div class="col-sm-4">
                    <label class="form-label" for="car-color">Color</label>
                    <input type="text" class="form-control" id="car-color" name="car-color" maxlength="63"
                        aria-describedby="color-limit" value="{{ $car->color }}" />
                    <div id="color-limit" class="form-text">Max 63 characters.</div>
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary me-2" disabled>Save</button>
                <a href="{{ route('users.destroy') }}" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to delete your account? This action is irreversible.');">Delete
                    Account</a>
            </div>
        </form>
    </main>
</x-layouts.main>
