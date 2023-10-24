<x-layouts.main title="{{ $user->name }}'s Profile" :$entries>
    <main class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="col-10 col-lg-8 col-xl-6 py-5">
            <div class="card" style="border-radius: .5rem;">
                <div class="row g-0">
                    <div class="col-md-4 gradient-custom text-center"
                        style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                        {{-- Only wrapped in div because can't give font icon margin for some reason --}}
                        <div class="my-4 w-75 mx-auto placeholder-glow">
                            @if ($pfp = $user->fetchFirstMedia())
                                <img src="{{ $pfp['file_url'] }}" alt="Profile picture"
                                    class="img-fluid rounded-circle shadow-lg">
                            @else
                                <i @class([
                                    'bi',
                                    'bi-person-circle',
                                    'placeholder' => $uploadedPfp ?? false,
                                ]) style="font-size: 6em;"></i>
                            @endif
                        </div>
                        <h4 class="display-6 mb-4">{{ $user->name }}</h4>
                        {{-- <div class="d-flex justify-content-center mb-2">
                            <a href="#!"><i class="bi bi-facebook fs-3 me-3"></i></a>
                            <a href="#!"><i class="bi bi-instagram fs-3 me-3"></i></a>
                            <a href="#!"><i class="bi bi-twitter fs-3"></i></a>
                        </div> --}}
                        {{-- Only wrapped in div because can't give font icon margin for some reason --}}
                        @if ($user->id == auth()->id())
                            <div class="mb-4">
                                <a href="{{ route('profile.edit') }}"><i class="bi bi-pencil-square fs-3"
                                        title="Edit profile"></i></a>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <h5>Information</h5>
                                <hr class="mt-0 mb-3">
                                <div class="row pt-1">
                                    <div class="col-md-6">
                                        <h6>Year</h6>
                                        <p class="text-muted">{{ $user->year_formatted }}</p>
                                        <h6>Major</h6>
                                        <p class="text-muted">{{ $user->major }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Bio</h6>
                                        <p class="text-muted">{{ $user->bio }}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5>Car</h5>
                                <hr class="mt-0 mb-3">
                                <div class="row pt-1">
                                    <div class="col-sm-4">
                                        <h6>License Plate</h6>
                                        <p class="text-muted">{{ $user->car->license_plate }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <h6>Make</h6>
                                        <p class="text-muted">{{ $user->car->make }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <h6>Color</h6>
                                        <p class="text-muted">{{ $user->car->color }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.main>
