<x-layouts.splash title="Request password reset" :$entries>
    <main class="mx-auto" style="max-width: min(800px, 80vw);">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title fs-3">Forgot password?</h1>
                <p class="card-subtitle fs-4 mb-3">Enter your email to receive a link to reset your
                    password.
                </p>
                <form action="{{ route('password.email') }}" method="POST" class="validate-on-change">
                    @csrf
                    <x-inputs.email class="mb-2" />
                    <button type="submit" class="btn btn-primary w-100">Reset</button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.splash>
