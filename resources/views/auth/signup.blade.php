<x-layouts.splash class="grid md:grid-cols-2" title="Sign up" :$entries>

    <div class="flex min-h-full flex-col flex-wrap items-center justify-center">
        <h1 class="my-10 text-center text-3xl font-semibold md:mt-0">Sign up for {{ config('app.name') }}</h1>
        <div class="w-full max-w-2xl px-3">
            <x-form action="{{ route('users.store') }}" withValidation>
                <div class="mb-6 flex gap-3">
                    <x-inputs.input class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" name="first-name"
                        size="lg" autocomplete="given-name" maxlength="255" placeholder="First name"
                        pattern="[^\d]+" required withValidation />
                    <x-inputs.input class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" name="last-name"
                        size="lg" autocomplete="family-name" maxlength="255" placeholder="Last name"
                        pattern="[^\d]+" required withValidation />
                </div>

                <div class="mb-6 flex gap-3">
                    <x-inputs.email class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" size="lg"
                        required withValidation />
                    <x-inputs.input class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" name="phone"
                        type="tel" x-data x-mask="(999) 999-9999" size="lg" autocomplete="tel-national"
                        placeholder="Phone number" required withValidation />
                    {{-- pattern="[0-9]{10}" maxlength="10" Removed in order for x-mask to work --}}
                    {{-- <div class="form-text" id="phone-help">No spaces or dashes</div> --}}
                </div>

                <div class="mb-6 flex gap-3">
                    <x-inputs.password class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0" size="lg"
                        autocomplete="new-password" minlength="8" maxlength="255" required withValidation />
                    <x-inputs.password class="!rounded-full !border-0 bg-gray-100 !px-5 !ring-offset-0"
                        name="password_confirmation" size="lg" autocomplete="new-password" minlength="8"
                        maxlength="255" placeholder="Confirm password" required withValidation />
                </div>

                <x-buttons.button
                    class="w-full !rounded-full bg-blue-500 text-white hover:bg-blue-600 active:bg-blue-700"
                    size="lg">Sign up</x-buttons.button>
            </x-form>
        </div>
        <p class="mt-10 font-medium text-slate-500">Already have an account? <a
                class="font-semibold text-blue-600 hover:underline" href="{{ route('sessions.create') }}">Sign in
                here!</a></p>
    </div>

</x-layouts.splash>
