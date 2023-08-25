<x-layouts.splash title="Sign in">
    <main class="d-flex flex-column align-items-center">
        <form class="mb-3 w-100" action="/signin" method="post">
            @csrf
            <h3 class="mb-4 fs-1 fw-normal text-white">Sign In</h3>

            @error('incorrect')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
            @enderror
            <div class="form-floating mb-3">
                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder=""
                    autocomplete="email" maxlength="255" pattern="[A-Za-z0-9]+@virginia.edu"
                    title="Valid UVA email (ex: computingID@virginia.edu)" value="{{ old('email') }}" required>
                <label for="email">UVA email address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control form-control-lg" id="password" name="password"
                    placeholder="" autocomplete="current-password" required>
                <label for="password">Password</label>
            </div>
            <button class="w-100 btn btn-uva-ow" type="submit">Sign in</button>
        </form>
        <p class="text-white">New user? <a class="orange orange-darken-hover no-decor" href="/signup">Sign up here!</a>
        </p>
    </main>
</x-layouts.splash>
