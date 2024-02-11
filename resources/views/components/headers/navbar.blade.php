<header
    class="min-h-20 flex items-center border-b-8 border-blue-900 bg-gradient-to-br from-blue-900 via-blue-500 to-blue-900 p-4">
    <nav class="flex flex-1 flex-wrap items-center gap-2">
        {{-- Branding --}}
        <a class="me-auto flex items-center text-4xl font-black italic text-white" href="{{ route('rides.index') }}"
            title="Return to ride listings">
            <x-fas-car class="h-12 w-12 sm:hidden" />
            <span class="hidden sm:block">{{ config('app.name') }}</span>
        </a>

        {{-- Notifications --}}
        <div class="relative" x-data="{
            open: false,
            toggle() {
                if (this.open) {
                    return this.close()
                }
        
                this.$refs.button.focus()
        
                this.open = true
            },
            close(focusAfter) {
                if (!this.open) return
        
                this.open = false
        
                focusAfter && focusAfter.focus()
            }
        }" x-on:keydown.escape.prevent.stop="close($refs.button)"
            x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']">
            {{-- Button --}}
            <button type="button" @class([
                'relative',
                'rounded-full',
                'p-2',
                'hover:bg-white/25',
                'after:absolute',
                'after:right-1',
                'after:top-1',
                'after:h-2.5',
                'after:w-2.5',
                'after:rounded-full',
                'after:bg-red-500',
                'after:hidden' => !auth()->user()->unreadNotifications()->exists(),
            ]) x-ref="button" x-on:click="toggle()"
                :aria-expanded="open" :aria-controls="$id('dropdown-button')">
                <x-far-bell class="h-8 w-8 text-white"></x-far-bell>
            </button>

            {{-- Panel --}}
            <div class="max-w-60 rounded-md bg-white text-left text-sm shadow-md" aria-label="Notifications Dropdown"
                x-cloak x-anchor.bottom-end="$refs.button" x-ref="panel" x-show="open" x-transition.origin.top.right
                x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')">
                @forelse (auth()->user()->notifications as $notification)
                    <div
                        class="flex w-full items-center gap-2 px-4 py-2.5 first-of-type:rounded-t-md last-of-type:rounded-b-md hover:bg-gray-50 disabled:text-gray-500">
                        <a class="flex flex-grow items-center"
                            href="{{ route('notifications.show', $notification['id']) }}">
                            {{-- @if ($notification['read_at'] === null)
                                <i class="bi bi-circle-fill text-danger me-2" style="font-size: 0.3rem"></i>
                            @endif --}}
                            {{ $notification['data']['message'] }}
                            <small
                                class="ms-auto text-gray-400">{{ $notification->created_at->diffForHumans() }}</small>
                        </a>
                        <x-buttons.form action="{{ route('notifications.destroy', $notification['id']) }}"
                            method="delete" withoutStyles>
                            <x-fas-times class="h-4 w-4 text-red-500 hover:text-red-600"></x-fas-times>
                        </x-buttons.form>
                    </div>
                @empty
                    <div class="text-nowrap flex w-full items-center rounded-md px-4 py-2.5 hover:bg-gray-50">No
                        notifications</div>
                @endforelse
            </div>
        </div>

        {{-- Account dropdown --}}
        <div class="relative" x-data="{
            open: false,
            toggle() {
                if (this.open) {
                    return this.close()
                }
        
                this.$refs.button.focus()
        
                this.open = true
            },
            close(focusAfter) {
                if (!this.open) return
        
                this.open = false
        
                focusAfter && focusAfter.focus()
            }
        }" x-on:keydown.escape.prevent.stop="close($refs.button)"
            x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']">
            {{-- Button --}}
            <button class="grid place-items-center rounded-full shadow" type="button" x-ref="button"
                x-on:click="toggle()" :aria-expanded="open" :aria-controls="$id('dropdown-button')">
                @if ($pfp = auth()->user()->fetchFirstMedia())
                    <img class="h-12 w-12" src="{{ $pfp['file_url'] }}" alt="Profile picture">
                @else
                    <x-fas-circle-user class="h-12 w-12"></x-fas-circle-user>
                @endif
            </button>

            {{-- Panel --}}
            <div class="z-40 w-32 rounded-md bg-white text-left shadow-md" aria-label="Account Dropdown" x-cloak
                x-anchor.bottom-end="$refs.button" x-ref="panel" x-show="open" x-transition.origin.top.right
                x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')">
                <a class="flex w-full items-center rounded-t-md px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                    href="{{ route('users.show') }}">Profile</a>

                <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                    href="{{ route('rides.index', ['my-rides' => 1]) }}">My Rides</a>

                <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                    href="{{ route('requests.index') }}" disabled>Requests</a>

                <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                    href="#" disabled>Messages</a>

                <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                    href="{{ route('alerts.index') }}">Alerts</a>

                <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                    href="{{ route('settings.index') }}">Settings</a>

                <x-buttons.form
                    class="flex w-full items-center gap-2 rounded-b-md border-t-2 px-4 py-2.5 text-red-500 hover:bg-gray-50 disabled:text-gray-500"
                    action="{{ route('sessions.destroy') }}" method="delete" withoutStyles>Sign out</x-buttons.form>
            </div>
        </div>
    </nav>
    <template id="notif-delete-form">
        <form action="" method="POST">
            @csrf
            @method('DELETE')
            <button class="dropdown-item" type="submit"><i class="bi bi-x-lg text-body-secondary"></i></button>
        </form>
    </template>
    @vite('resources/js/notifications.js')
</header>
