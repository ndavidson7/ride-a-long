<x-layouts.main title="Sign in" :with-navbar="false">
    <main class="d-flex flex-column align-items-center">
        <form class="mb-2 w-100" action="/signin" method="post">
            @csrf
            <h3 class="mb-3 fs-1 fw-normal text-white">Sign In</h3>

            <div class="form-floating mb-1">
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                    id="email" name="email" placeholder="computingID@virginia.edu"
                    pattern="[A-Za-z0-9]+@virginia.edu" title="Valid UVA email (computingID@virginia.edu)"
                    autocomplete="email" @error('email') @else value="{{ old('email') }}" @enderror required>
                <label for="email">UVA email address</label>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-floating mb-2">
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                    id="password" name="password" placeholder="Password" autocomplete="current-password" required>
                <label for="password">Password</label>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button class="w-100 btn btn-uva-ow" type="submit">Sign in</button>
        </form>
        <p class="text-white">New user? <a class="orange orange-darken-hover" href="/signup">Sign up</a> here!</p>
    </main>
</x-layouts.main>
