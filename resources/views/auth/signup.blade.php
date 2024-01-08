<x-layouts.splash title="Sign up" :$entries>
    <main>
        <div class="container-fluid align-items-center col-10 col-md-8 col-lg-6 col-lg-5 col-xxl-4">
            <form class="row disabled-until-required validate-on-change d-flex flex-column align-items-center gap-3 mb-3"
                action="{{ route('users.store') }}" method="post">
                @csrf
                {{-- EMPTY PLACEHOLDERS NECESSARY FOR CSS STYLING ON INVALID INPUTS --}}
                <div class="d-flex gap-3">
                    <div class="form-floating col-sm-6">
                        <input type="text" @class([
                            'form-control',
                            'form-control-lg',
                            'is-invalid' => $errors->has('first-name'),
                        ]) id="first-name" name="first-name" placeholder=""
                            autocomplete="given-name" maxlength="255" pattern="[^\d]+" value="{{ old('first-name') }}"
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
                        <input type="text" @class([
                            'form-control',
                            'form-control-lg',
                            'is-invalid' => $errors->has('last-name'),
                        ]) id="last-name" name="last-name"
                            placeholder="" autocomplete="family-name" maxlength="255" pattern="[^\d]+"
                            value="{{ old('last-name') }}" aria-describedby="name-help" required />
                        <label for="last-name">Last name</label>
                        <div class="invalid-feedback">
                            @error('last-name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <x-inputs.email class="col-sm-6" with-help />
                    <div class="form-floating col-sm-6">
                        <input type="tel" @class([
                            'form-control',
                            'form-control-lg',
                            'is-invalid' => $errors->has('phone'),
                        ]) id="phone" name="phone" placeholder=""
                            pattern="[0-9]{10}" autocomplete="tel-national" maxlength="10" aria-describedby="phone-help"
                            value="{{ old('phone') }}" required />
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
                        <input type="password" @class([
                            'form-control',
                            'form-control-lg',
                            'is-invalid' => $errors->has('password'),
                        ]) id="password" name="password"
                            placeholder="" autocomplete="new-password" minlength="8" maxlength="255"
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
                        <input type="password" @class([
                            'form-control',
                            'form-control-lg',
                            'is-invalid' => $errors->has('password_confirmation'),
                        ]) id="password_confirmation"
                            name="password_confirmation" placeholder="" autocomplete="new-password" minlength="8"
                            maxlength="255" aria-describedby="password-help" required />
                        <label for="password_confirmation">Confirm password</label>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>
                <button class="w-50 btn btn-primary" type="submit" disabled>Sign up</button>
            </form>
            <div class="row text-center">
                <p>Already have an account? <a class="link-primary" href="{{ route('sessions.create') }}">Sign in
                        here!</a></p>
            </div>
        </div>
    </main>
</x-layouts.splash>
