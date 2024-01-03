<x-layouts.splash title="Verify email" :entries="[]">
    <main class="mx-auto" style="max-width: min(800px, 80vw);">
        <h1>Check your email!</h1>
        <p class="fs-4">For the safety of all users, you must verify your email before you can use this site. Click
            the link in the email that was sent to you. If you didn't receive an email, click the button below to resend
            it.</p>
        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Resend</button>
        </form>
    </main>
</x-layouts.splash>
