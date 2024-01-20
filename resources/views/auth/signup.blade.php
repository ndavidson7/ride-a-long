<x-layouts.splash title="Sign up">

    <h1 class="mb-10 text-center text-3xl font-semibold">Sign up for {{ config('app.name') }}</h1>
    <div class="w-full max-w-2xl">
        <x-form action="{{ route('users.store') }}" withValidation>
            <div class="mb-4 grid grid-cols-[repeat(auto-fit,_minmax(min(275px,_100%),_1fr))] gap-x-4 gap-y-2">
                <x-inputs.input class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" name="first-name"
                    size="lg" autocomplete="given-name" maxlength="255" placeholder="First name" pattern="[^\d]+"
                    required withValidation />
                <x-inputs.input class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" name="last-name"
                    size="lg" autocomplete="family-name" maxlength="255" placeholder="Last name" pattern="[^\d]+"
                    required withValidation />
                <x-inputs.email class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" size="lg" required
                    withValidation />
                <x-inputs.input class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" name="phone"
                    type="tel" x-data x-mask="(999) 999-9999" size="lg" autocomplete="tel-national"
                    placeholder="Phone number" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required withValidation />
                <x-inputs.password class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" size="lg"
                    autocomplete="new-password" required withValidation />
                <x-inputs.password class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0"
                    name="password_confirmation" size="lg" autocomplete="new-password"
                    placeholder="Confirm password" required withValidation />
            </div>

            <x-buttons.button class="w-full !rounded-full bg-blue-500 text-white hover:bg-blue-600 active:bg-blue-700"
                size="lg" withValidation>Sign up</x-buttons.button>
        </x-form>
    </div>
    <p class="mt-10 font-medium text-slate-500">Already have an account? <a
            class="font-semibold text-blue-600 hover:underline" href="{{ route('sessions.create') }}">Sign in
            here!</a></p>

</x-layouts.splash>
