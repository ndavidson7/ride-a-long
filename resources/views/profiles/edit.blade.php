<x-layouts.main title="Profile" :$entries>
    <main>
        {{-- <div class="container-fluid d-flex flex-column align-items-center col-md-6 mb-5 mb-md-0">
        <p style="font-size:2em;font-weight:500;">{{ $user->name }}</p>
        <i class="fa-solid fa-user mb-2" style="font-size:300px;"></i>
        <label for="pfp" class="form-label">Upload profile picture</label>
        <input class="form-control w-75" type="file" id="pfp" name="pfp" accept="image/*">
    </div> --}}
        <div class="container-fluid d-flex flex-column align-items-center">
            <div class="row w-100 py-4">
                <form class="form-disabled" action="{{ route('profile.update') }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="row col-11 col-sm-10 col-md-9 col-lg-8 col-xl-7 col-xxl-6 mx-auto mb-4">
                        <h3>Profile</h3>
                        <div class="col-sm-6 mb-3">
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
                        <div class="col-sm-6 mb-3">
                            <label class="form-label" for="major">Major</label>
                            <input type="text" class="form-control" id="major" name="major" maxlength="63"
                                aria-describedby="majorLimit" value="{{ $user->major }}" />
                            <div id="majorLimit" class="form-text">Max 63 characters.</div>
                        </div>
                        <div>
                            <label class="form-label" for="bio">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows=3 maxlength="255" aria-describedby="bioLimit">{{ $user->bio }}</textarea>
                            <div id="bioLimit" class="form-text">Max 255 characters.</div>
                        </div>
                    </div>
                    <div class="row col-11 col-sm-10 col-md-9 col-lg-8 col-xl-7 col-xxl-6 mx-auto mb-4">
                        <h3>Car Info</h3>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label" for="car-license-plate">License Plate</label>
                            <input type="text" class="form-control" id="car-license-plate" name="car-license-plate"
                                maxlength="7" aria-describedby="plateLimit" value="{{ $car->license_plate }}" />
                            <div id="plateLimit" class="form-text">Max 7 characters.</div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label" for="car-make">Make</label>
                            <input type="text" class="form-control" id="car-make" name="car-make" maxlength="63"
                                aria-describedby="makeLimit" value="{{ $car->make }}" />
                            <div id="makeLimit" class="form-text">Max 63 characters.</div>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="car-color">Color</label>
                            <input type="text" class="form-control" id="car-color" name="car-color" maxlength="63"
                                aria-describedby="colorLimit" value="{{ $car->color }}" />
                            <div id="colorLimit" class="form-text">Max 63 characters.</div>
                        </div>
                    </div>
                    <div class="row col-11 col-sm-10 col-md-9 col-lg-8 col-xl-7 col-xxl-6 mx-auto mb-4">
                        <h3>Emergency Contacts</h3>
                        @foreach ($contacts as $contact)
                            <h4>Contact {{ $loop->iteration }}</h4>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="contact{{ $loop->iteration }}-first-name">First
                                    Name</label>
                                <input type="text" class="form-control" id="contact{{ $loop->iteration }}-first-name"
                                    name="contact{{ $loop->iteration }}-first-name" maxlength="255"
                                    value="{{ $contact->first_name }}" />
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="contact{{ $loop->iteration }}-last-name">Last
                                    Name</label>
                                <input type="text" class="form-control" id="contact{{ $loop->iteration }}-last-name"
                                    name="contact{{ $loop->iteration }}-last-name" maxlength="255"
                                    value="{{ $contact->last_name }}" />
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="contact{{ $loop->iteration }}-phone">Phone
                                    Number</label>
                                <input type="tel" class="form-control" id="contact{{ $loop->iteration }}-phone"
                                    name="contact{{ $loop->iteration }}-phone"
                                    placeholder="No spaces, no dashes (ex: 1112223333)" pattern="[0-9]{10}"
                                    value="{{ $contact->phone }}" />
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label"
                                    for="contact{{ $loop->iteration }}-relationship">Relationship</label>
                                <input type="text" class="form-control"
                                    id="contact{{ $loop->iteration }}-relationship"
                                    name="contact{{ $loop->iteration }}-relationship" maxlength="63"
                                    value="{{ $contact->relationship }}" />
                            </div>
                        @endforeach
                        <button type="button" id="add-new-contact" class="btn btn-uva-ob" style="width: auto;">Add
                            New</button>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-uva-ob me-2" disabled>Save</button>
                        <a href="{{ route('profile.destroy') }}" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete your account? This action is irreversible.');">Delete
                            Account</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-layouts.main>
