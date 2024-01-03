<x-layouts.splash title="Sign in" :$entries>
    <main class="d-flex flex-column align-items-center">
        <form class="disabled-until-required validate-on-change mb-3 d-flex flex-column align-items-center"
            action="{{ route('sessions.store') }}" method="post" style="width: 320px">
            @csrf
            @error('incorrect')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
            @enderror
            <x-inputs.email class="mb-3 w-100" />
            <div class="form-floating mb-3 w-100">
                <input type="password" class="form-control form-control-lg" id="password" name="password"
                    placeholder="" autocomplete="current-password" required />
                <label for="password">Password</label>
                <div class="invalid-feedback"></div>
            </div>
            <div class="d-flex mb-3 gap-4">
                <div class="form-check mb-3 mb-md-0">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1"
                        @checked(old('remember')) />
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" class="link-primary">Forgot password?</a>
            </div>
            <button class="btn btn-primary w-50" type="submit" disabled>Sign in</button>
        </form>
        <p>New user? <a class="link-primary" href="{{ route('users.create') }}">Sign up here!</a></p>
    </main>
</x-layouts.splash>
