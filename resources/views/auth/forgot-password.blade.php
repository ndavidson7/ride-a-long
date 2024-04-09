<x-layouts.splash title="Request password reset">

    <div class="w-full max-w-md">
        <h1 class="text-3xl font-semibold">Forgot your password?</h1>
        <p class="mb-8 text-gray-500">Enter your email address and we'll send you a link to reset your
            password.</p>
        <x-form action="{{ route('password.email') }}" validated>
            <div class="relative mb-2">
                <span class="absolute left-0 top-[18px] flex items-center pl-5">
                    <x-fas-user class="h-5 w-5" />
                </span>
                <x-inputs.email class="!rounded-full !border-0 bg-gray-100 !pl-12 !pr-5 !ring-offset-0" size="lg"
                    required validated />
            </div>
            <x-button class="w-full !rounded-full bg-blue-500 text-white hover:bg-blue-600 active:bg-blue-700"
                size="lg" validated>Request password reset</x-button>
        </x-form>
    </div>

</x-layouts.splash>
