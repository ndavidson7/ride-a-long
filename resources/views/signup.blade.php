<x-layouts.main title="Sign up" :with-navbar="false">
    <main class="d-flex flex-column align-items-center">
        <form class="mb-2 w-100" action="/signup" method="post">
            @csrf
            <h3 class="mb-3 fs-1 fw-normal text-white">Sign Up</h3>

            <div class="form-floating mb-1">
                <input type="text" class="form-control form-control-lg @error('first-name') is-invalid @enderror"
                    id="first-name" name="first-name" placeholder="First name" autocomplete="given-name"
                    @error('first-name') @else value="{{ old('first-name') }}" @enderror required>
                <label for="first-name">First name</label>
                @error('first-name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-1">
                <input type="text" class="form-control form-control-lg @error('last-name') is-invalid @enderror"
                    id="last-name" name="last-name" placeholder="Last name" autocomplete="family-name"
                    @error('last-name') @else value="{{ old('last-name') }}" @enderror required>
                <label for="last-name">Last name</label>
                @error('last-name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-1">
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                    id="email" name="email" placeholder="computingID@virginia.edu" autocomplete="email"
                    pattern="[A-Za-z0-9]+@virginia.edu" title="Valid UVA email (computingID@virginia.edu)"
                    @error('email') @else value="{{ old('email') }}" @enderror required>
                <label for="email">UVA email address</label>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-1">
                <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror"
                    id="phone" name="phone" placeholder="1234567890 (No spaces, no dashes)" pattern="[0-9]{10}"
                    autocomplete="tel-national" @error('phone') @else value="{{ old('phone') }}" @enderror required>
                <label for="phone">Personal phone number</label>
                @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-1">
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                    id="password" name="password" placeholder="Password" autocomplete="new-password"
                    @error('password') @else value="{{ old('password') }}" @enderror required>
                <label for="password">Password</label>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control form-control-lg @error('password2') is-invalid @enderror"
                    id="passwordConfirm" name="password2" placeholder="Password" autocomplete="new-password"
                    @error('password2') @else value="{{ old('password2') }}" @enderror required>
                <label for="passwordConfirm">Repeat Password</label>
                @error('password2')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button class="w-100 btn btn-uva-ow" type="submit">Sign up</button>
        </form>
        <p class="text-white">Already have an account? <a class="orange orange-darken-hover" href="/signin">Sign in</a>
            here!</p>
    </main>
</x-layouts.main>
