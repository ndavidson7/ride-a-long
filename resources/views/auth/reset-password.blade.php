<x-layouts.splash title="Reset password">

    <h1 class="mb-10 text-center text-3xl font-semibold">Reset password</h1>
    <div class="w-full max-w-md">
        <x-form action="{{ route('password.update') }}" withValidation>
            <div class="relative mb-2">
                <span class="absolute left-0 top-[18px] flex items-center pl-5">
                    <x-fas-user class="h-5 w-5" />
                </span>
                <x-inputs.email class="!rounded-full !border-0 bg-gray-100 !pl-12 !pr-5 !ring-offset-0" size="lg"
                    required withValidation />
            </div>
            <div class="relative mb-2">
                <span class="absolute left-0 top-[18px] flex items-center pl-5">
                    <x-fas-key class="h-5 w-5" />
                </span>
                <x-inputs.password class="!rounded-full !border-0 bg-gray-100 !pl-12 !pr-5 !ring-offset-0"
                    size="lg" autocomplete="new-password" placeholder="New password" required withValidation />
            </div>
            <div class="relative mb-4">
                <span class="absolute left-0 top-[18px] flex items-center pl-5">
                    <x-fas-key class="h-5 w-5" />
                </span>
                <x-inputs.password class="!rounded-full !border-0 bg-gray-100 !pl-12 !pr-5 !ring-offset-0"
                    name="password_confirmation" size="lg" autocomplete="new-password"
                    placeholder="Confirm password" required withValidation />
            </div>
            <input name="token" type="hidden" value="{{ $token }}" />
            <x-button class="w-full !rounded-full bg-blue-500 text-white hover:bg-blue-600 active:bg-blue-700"
                size="lg" withValidation>Reset</x-button>
        </x-form>
    </div>

</x-layouts.splash>
