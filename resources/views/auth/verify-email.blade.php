<x-layouts.splash title="Verify email" :entries="[]">
    <main class="text-center text-white py-auto">
        <h2>For the safety of all users, you have to verify your email before you can use this site.</h2>
        <h3>Click the link in the email that was sent to you. If you didn't receive an email, click the button below to
            resend it.</h3>
        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-uva-ow">Resend</button>
        </form>
    </main>
</x-layouts.splash>
