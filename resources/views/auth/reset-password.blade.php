<x-layouts.splash title="Reset password" :$entries>
    <main class="mx-auto">
        <div class="card" style="width: min(500px, 80vw);">
            <div class="card-body">
                <h1 class="card-title fs-3">Reset password</h1>
                <p class="card-subtitle fs-4 mb-3">Enter a new password.</p>
                <form action="{{ route('password.update') }}" method="POST" disabled>
                    @csrf
                    <x-inputs.email class="mb-2" />
                    <x-inputs.floating type="password" class="mb-2" name="password" id="password" minlength="8"
                        maxlength="255" autocomplete="new-password" required>
                        <x-slot:label>Password</x-slot:label>
                    </x-inputs.floating>
                    <x-inputs.floating type="password" class="mb-2" name="password_confirmation"
                        id="password_confirmation" minlength="8" maxlength="255" autocomplete="new-password" required>
                        <x-slot:label>Confirm Password</x-slot:label>
                    </x-inputs.floating>
                    <input type="hidden" name="token" value="{{ $token }}" />
                    <button type="submit" class="btn btn-primary w-100">Reset</button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.splash>
