<x-layouts.splash class="grid md:grid-cols-2" title="Sign in" :$entries>

    <div class="flex min-h-full flex-col flex-wrap items-center justify-center">
        <h1 class="my-10 text-center text-3xl font-semibold md:mt-0">Sign in to {{ config('app.name') }}</h1>
        <div class="w-full max-w-md px-3">
            <x-buk-form action="{{ route('sessions.store') }}">
                @error('incorrect')
                    <div class="" role="alert">
                        {{ $message }}
                    </div>
                @enderror
                <div class="relative mb-6">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-5">
                        <x-fas-user class="h-5 w-5" />
                    </span>
                    <x-inputs.email class="!rounded-full !border-0 bg-gray-100 !pl-12 !pr-5 !ring-offset-0"
                        size="lg" required />
                </div>
                <div class="relative mb-6">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-5">
                        <x-fas-key class="h-5 w-5" />
                    </span>
                    <x-inputs.input class="!rounded-full !border-0 bg-gray-100 !pl-12 !pr-5 !ring-offset-0"
                        name="password" type="password" size="lg" placeholder="Password"
                        autocomplete="current-password" required />
                </div>
                {{-- <input class="form-control form-control-lg" id="password" name="password" type="password"
                            placeholder="" autocomplete="current-password" required /> --}}
                <div class="mb-8 flex flex-wrap items-center gap-2">
                    <x-inputs.checkbox name="remember" value="1" />
                    <x-buk-label class="me-auto font-medium" for="remember">Remember me</x-buk-label>
                    <a class="font-semibold text-blue-600 hover:underline" href="{{ route('password.request') }}">Forgot
                        password?</a>
                </div>
                <x-buttons.button
                    class="w-full !rounded-full bg-blue-500 text-white hover:bg-blue-600 active:bg-blue-700"
                    size="lg">Sign
                    in</x-buttons.button>
            </x-buk-form>
        </div>
        <p class="mt-10 font-medium text-slate-500">New user? <a class="font-semibold text-blue-600 hover:underline"
                href="{{ route('users.create') }}">Sign up here!</a></p>
    </div>

</x-layouts.splash>
