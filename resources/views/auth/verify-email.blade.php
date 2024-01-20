<x-layouts.splash title="Verify email" :entries="[]">

    <h1 class="mb-4 text-3xl font-semibold">Check your email!</h1>
    <p class="mb-4 max-w-[50ch] text-lg text-black/75">For the safety of all users, you must verify your email before
        you can use this site. Click the link in the email that was sent to you. If you didn't receive an email,
        click the button below to resend it.</p>
    <x-buttons.form class="!rounded-full bg-blue-500 text-white hover:bg-blue-600 active:bg-blue-700"
        action="{{ route('verification.send') }}" size="lg">Resend</x-buttons.form>

</x-layouts.splash>
