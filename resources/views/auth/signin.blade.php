<x-layouts.splash class="grid md:grid-cols-2" title="Sign in" :entries="[]">

    <div class="flex min-h-full flex-col flex-wrap items-center justify-center py-10">
        <h1 class="mb-10 text-center text-3xl font-semibold">Sign in to {{ config('app.name') }}</h1>
        <div class="w-full max-w-md px-3">
            <x-form action="{{ route('sessions.store') }}" withValidation>
                @error('incorrect')
                    <div class="relative mb-6 w-full rounded-lg bg-red-500 p-4 text-white [&>svg+div]:translate-y-[-3px] [&>svg]:absolute [&>svg]:left-4 [&>svg]:top-4 [&>svg]:fill-white [&>svg~*]:pl-7"
                        role="alert">
                        <x-fas-circle-xmark class="h-4 w-4" />
                        <h2 class="font-medium leading-none tracking-tight">{{ $message }}</h2>
                    </div>
                @enderror

                <div class="relative mb-2">
                    <span class="absolute left-0 top-[18px] flex items-center pl-5">
                        <x-fas-user class="h-5 w-5" />
                    </span>
                    <x-inputs.email class="!rounded-full !border-0 bg-gray-100 !pl-12 !pr-5 !ring-offset-0"
                        size="lg" required withValidation />
                </div>

                <div class="relative mb-2">
                    <span class="absolute left-0 top-[18px] flex items-center pl-5">
                        <x-fas-key class="h-5 w-5" />
                    </span>
                    <x-inputs.password class="!rounded-full !border-0 bg-gray-100 !pl-12 !pr-5 !ring-offset-0"
                        size="lg" required withValidation />
                </div>

                <div class="mb-8 flex flex-wrap items-center gap-2">
                    <x-inputs.checkbox name="remember" value="1" />
                    <x-buk-label class="me-auto font-medium" for="remember">Remember me</x-buk-label>
                    <a class="font-semibold text-blue-600 hover:underline" href="{{ route('password.request') }}">Forgot
                        password?</a>
                </div>

                <x-buttons.button
                    class="w-full !rounded-full bg-blue-500 text-white hover:bg-blue-600 active:bg-blue-700"
                    size="lg" withValidation>Sign in</x-buttons.button>
            </x-form>
        </div>
        <p class="mt-10 font-medium text-slate-500">New user? <a class="font-semibold text-blue-600 hover:underline"
                href="{{ route('users.create') }}">Sign up here!</a></p>
    </div>

</x-layouts.splash>
