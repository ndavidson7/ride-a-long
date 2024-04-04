<x-layouts.splash title="Verify email">

    <div class="w-full max-w-[50ch]">
        <h1 class="text-3xl font-semibold">Check your email!</h1>
        <p class="mb-4 text-lg text-gray-500">For the safety of all users, you must verify your email before
            you can use this site. Click the link in the email that was sent to you. If you didn't receive an email,
            click the button below to resend it.</p>
        <x-button class="w-full !rounded-full bg-blue-500 text-white hover:bg-blue-600 active:bg-blue-700" as="form"
            action="{{ route('verification.send') }}" size="lg">Resend</x-button>
    </div>

</x-layouts.splash>
