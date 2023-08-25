<x-layouts.splash title="Sign up">
    <main class="d-flex flex-column align-items-center">
        <form class="mb-3 w-100" action="/signup" method="post">
            @csrf
            <h3 class="mb-4 fs-1 fw-normal text-white">Sign Up</h3>

            {{-- EMPTY PLACEHOLDERS NECESSARY FOR CSS STYLING ON INVALID INPUTS --}}
            <div class="form-floating mb-3">
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                    id="email" name="email" placeholder="" autocomplete="email" maxlength="255"
                    pattern="[A-Za-z0-9]+@virginia.edu" title="Valid UVA email (ex: computingID@virginia.edu)"
                    @error('email') @else value="{{ old('email') }}" @enderror required>
                <label for="email">UVA email address</label>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                    id="phone" name="phone" placeholder="" title="No spaces, no dashes (ex: 1234567890)"
                    pattern="[0-9]{10}" autocomplete="tel-national" maxlength="10"
                    @error('phone') @else value="{{ old('phone') }}" @enderror required>
                <label for="phone">Personal phone number</label>
                @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control form-control-lg @error('first-name') is-invalid @enderror"
                    id="first-name" name="first-name" placeholder="" autocomplete="given-name" maxlength="255"
                    pattern="[^\d\s]+" @error('first-name') @else value="{{ old('first-name') }}" @enderror required>
                <label for="first-name">First name</label>
                @error('first-name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control form-control-lg @error('last-name') is-invalid @enderror"
                    id="last-name" name="last-name" placeholder="" autocomplete="family-name" maxlength="255"
                    pattern="[^\d\s]+" @error('last-name') @else value="{{ old('last-name') }}" @enderror required>
                <label for="last-name">Last name</label>
                @error('last-name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                    id="password" name="password" placeholder="" autocomplete="new-password" maxlength="255" required>
                <label for="password">Password</label>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="password"
                    class="form-control form-control-lg @error('confirm-password') is-invalid @enderror"
                    id="confirm-password" name="confirm-password" placeholder="" autocomplete="new-password"
                    maxlength="255" required>
                <label for="confirm-password">Confirm Password</label>
            </div>
            <button class="w-100 btn btn-uva-ow" type="submit">Sign up</button>
        </form>
        <p class="text-white">Already have an account? <a class="orange orange-darken-hover no-decor"
                href="/signin">Sign in here!</a></p>
    </main>
</x-layouts.splash>
