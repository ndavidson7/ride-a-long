<x-layouts.splash title="Sign up" :$entries>
    <main>
        <div class="container-fluid align-items-center col-10 col-md-8 col-lg-6 col-lg-5 col-xxl-4">
            <form class="row disabled-until-required validate-on-change d-flex flex-column align-items-center gap-3 mb-3"
                action="{{ route('users.store') }}" method="post">
                @csrf
                {{-- EMPTY PLACEHOLDERS NECESSARY FOR CSS STYLING ON INVALID INPUTS --}}
                <div class="d-flex gap-3">
                    <div class="form-floating col-sm-6">
                        <input type="text"
                            class="form-control form-control-lg @error('first-name') is-invalid @enderror" id="first-name"
                            name="first-name" placeholder="" autocomplete="given-name" maxlength="255" pattern="[^\d]+"
                            @error('first-name') @else value="{{ old('first-name') }}" @enderror
                            aria-describedby="name-help" required />
                        <label for="first-name">First name</label>
                        <div class="form-text" id="name-help">No numbers</div>
                        <div class="invalid-feedback">
                            @error('first-name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-floating col-sm-6">
                        <input type="text"
                            class="form-control form-control-lg @error('last-name') is-invalid @enderror" id="last-name"
                            name="last-name" placeholder="" autocomplete="family-name" maxlength="255" pattern="[^\d]+"
                            @error('last-name') @else value="{{ old('last-name') }}" @enderror
                            aria-describedby="name-help" required />
                        <label for="last-name">Last name</label>
                        <div class="invalid-feedback">
                            @error('last-name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="form-floating col-sm-6">
                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                            id="email" name="email" placeholder="" autocomplete="email" maxlength="255"
                            pattern="[A-Za-z0-9]+@virginia.edu" aria-describedby="email-help"
                            @error('email') @else value="{{ old('email') }}" @enderror required />
                        <label for="email">UVA email address</label>
                        <div class="form-text" id="email-help">Valid UVA email</div>
                        <div class="invalid-feedback">
                            @error('email')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-floating col-sm-6">
                        <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                            id="phone" name="phone" placeholder="" pattern="[0-9]{10}" autocomplete="tel-national"
                            maxlength="10" aria-describedby="phone-help"
                            @error('phone') @else value="{{ old('phone') }}" @enderror required />
                        <label for="phone">Personal phone number</label>
                        <div class="form-text" id="phone-help">No spaces or dashes</div>
                        <div class="invalid-feedback">
                            @error('phone')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="form-floating col-sm-6">
                        <input type="password"
                            class="form-control form-control-lg @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="" autocomplete="new-password" maxlength="255"
                            aria-describedby="password-help" required />
                        <label for="password">Password</label>
                        <div class="form-text" id="password-help">Never reuse
                            passwords!</div>
                        <div class="invalid-feedback">
                            @error('password')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-floating col-sm-6">
                        <input type="password"
                            class="form-control form-control-lg @error('confirm-password') is-invalid @enderror"
                            id="confirm-password" name="confirm-password" placeholder="" autocomplete="new-password"
                            maxlength="255" aria-describedby="password-help" required />
                        <label for="confirm-password">Confirm Password</label>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>
                <button class="w-50 btn" type="submit" disabled>Sign up</button>
            </form>
            <div class="row text-center">
                <p>Already have an account? <a class="link-primary" href="{{ route('sessions.create') }}">Sign in
                        here!</a></p>
            </div>
        </div>
    </main>
</x-layouts.splash>
