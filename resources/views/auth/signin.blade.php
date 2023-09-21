<x-layouts.splash title="Sign in" :$entries>
    <main>
        <div class="d-flex justify-content-center">
            <div class="text-center" style="width: 26rem;">
                <form class="mb-3 form-disabled" action="{{ route('sessions.store') }}" method="post">
                    @csrf
                    <h1 class="mb-4 fw-normal text-white">Sign In</h1>
                    @error('incorrect')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control form-control-lg" id="email" name="email"
                            placeholder="" autocomplete="email" maxlength="255" pattern="[A-Za-z0-9]+@virginia.edu"
                            title="Valid UVA email (ex: computingID@virginia.edu)" value="{{ old('email') }}"
                            required />
                        <label for="email">UVA email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control form-control-lg" id="password" name="password"
                            placeholder="" autocomplete="current-password" required />
                        <label for="password">Password</label>
                    </div>
                    <div class="row mb-3 text-white">
                        <div class="col-sm-6 d-flex justify-content-center">
                            <div class="form-check mb-3 mb-md-0 align-items-center">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                        </div>
                        <div class="col-sm-6 d-flex justify-content-center">
                            <a href="#!" class="orange orange-darken-hover no-decor">Forgot password?</a>
                        </div>
                    </div>
                    <button class="btn btn-uva-ow" type="submit" style="width: 13rem;" disabled>Sign in</button>
                </form>
                <p class="text-white">New user? <a class="orange orange-darken-hover no-decor" href="/signup">Sign up
                        here!</a>
                </p>
            </div>
        </div>
    </main>
</x-layouts.splash>
