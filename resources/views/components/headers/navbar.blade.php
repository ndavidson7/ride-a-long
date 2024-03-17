<header
    class="min-h-20 flex items-center border-b-8 border-blue-900 bg-gradient-to-br from-blue-900 via-blue-500 to-blue-900 p-4">
    <nav class="flex flex-1 flex-wrap items-center gap-2">
        {{-- Branding --}}
        <a class="me-auto flex items-center text-4xl font-black italic text-white" href="{{ route('rides.index') }}"
            title="Return to ride listings">
            <x-fas-car class="size-12 sm:hidden" />
            <span class="hidden sm:block">{{ config('app.name') }}</span>
        </a>

        {{-- Notifications --}}
        <div class="relative" x-data="{
            open: false,
            notifications: {{ Js::from(auth()->user()->notifications) }},
        
            get allRead() {
                return this.notifications.every(notification => notification.read_at !== null);
            },
        
            init() {
                {{-- Listen for new notifications --}}
                Echo.private('App.Models.User.' + window.userId).notification(
                    (notification) => {
                        // Add the notification to the list
                        notification.read_at = null;
                        this.notifications.unshift(notification);
        
                        // Create and show a toast
                        {{-- TODO: Fix toasts --}}
                        {{-- makeToast(data); --}}
                    }
                );
            },
        
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
        }" @keydown.escape.prevent.stop="close($refs.button)"
            x-id="['dropdown-button']">
            {{-- Button --}}
            <button
                class="after:size-2.5 before:size-2.5 relative rounded-full p-2 before:absolute before:right-2.5 before:top-2.5 before:animate-ping before:rounded-full before:bg-yellow-300 before:opacity-75 after:absolute after:right-2.5 after:top-2.5 after:rounded-full after:bg-yellow-300 hover:bg-white/25"
                type="button" :class="allRead && 'before:hidden after:hidden'" x-ref="button" @click="toggle()"
                :aria-expanded="open" :aria-controls="$id('dropdown-button')">
                <x-far-bell class="size-8 text-white"></x-far-bell>
            </button>

            {{-- Panel --}}
            <div class="z-40 rounded-md bg-white text-sm shadow-md" aria-label="Notifications Dropdown" x-cloak
                x-anchor.bottom-end="$refs.button" x-show="open" x-transition.origin.top.right
                @click.outside="close($refs.button)" :id="$id('dropdown-button')">
                <ol class="w-screen text-left sm:w-[400px]" x-cloak x-show="notifications.length > 0">
                    <template x-for="notification in notifications" :key="notification.id">
                        <li class="group relative flex items-center justify-between gap-2 py-2.5 pl-6 pr-4 first-of-type:rounded-t-md last-of-type:rounded-b-md hover:bg-gray-50 disabled:text-gray-500"
                            :class="notification.read_at === null ?
                                'before:size-2 before:rounded-full before:bg-yellow-300 before:absolute before:top-1/2 before:left-2 before:-translate-y-1/2' :
                                ''">
                            <a class="flex-grow" :href="route('notifications.show', notification.id)">
                                <p x-text="notification.data.message"></p>
                                <time class="text-xs text-gray-400" x-text="dayjs(notification.created_at).fromNow()"
                                    :datetime="notification.created_at"></time>
                            </a>
                            <x-buttons.form class="grid place-items-center sm:invisible sm:group-hover:visible"
                                ::action="route('notifications.destroy', notification.id)" method="delete" withoutStyles>
                                <x-fas-times class="size-4 text-red-500 hover:text-red-600" />
                            </x-buttons.form>
                        </li>
                    </template>
                </ol>
                <div class="text-nowrap rounded-md px-4 py-2.5" x-cloak x-show="notifications.length === 0">No
                    notifications</div>
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
        }" @keydown.escape.prevent.stop="close($refs.button)"
            x-id="['dropdown-button']">
            {{-- Button --}}
            <button class="grid place-items-center rounded-full" type="button" x-ref="button" @click="toggle()"
                :aria-expanded="open" :aria-controls="$id('dropdown-button')">
                @if ($pfp = auth()->user()->fetchFirstMedia())
                    <img class="size-12" src="{{ $pfp['file_url'] }}" alt="Profile picture">
                @else
                    <x-fas-circle-user class="size-12 text-white" />
                @endif
            </button>

            {{-- Panel --}}
            <ul class="z-40 w-32 rounded-md bg-white text-left shadow-md" aria-label="Account Dropdown" x-cloak
                x-anchor.bottom-end="$refs.button" x-show="open" x-transition.origin.top.right
                @click.outside="close($refs.button)" :id="$id('dropdown-button')">
                <li>
                    <a class="flex w-full items-center rounded-t-md px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                        href="{{ route('users.show') }}">Profile</a>
                </li>

                <li>
                    <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                        href="{{ route('rides.index', ['my-rides' => 1]) }}">My Rides</a>
                </li>

                <li>
                    <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                        href="{{ route('requests.index') }}" disabled>Requests</a>
                </li>

                <li>
                    <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                        href="#" disabled>Messages</a>
                </li>

                <li>
                    <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                        href="{{ route('alerts.index') }}">Alerts</a>
                </li>

                <li>
                    <a class="flex w-full items-center gap-2 px-4 py-2.5 hover:bg-gray-50 disabled:text-gray-500"
                        href="{{ route('settings.index') }}">Settings</a>
                </li>

                <li>
                    <x-buttons.form
                        class="flex w-full items-center gap-2 rounded-b-md border-t-2 px-4 py-2.5 text-red-500 hover:bg-gray-50 disabled:text-gray-500"
                        action="{{ route('sessions.destroy') }}" method="delete" withoutStyles>Sign
                        out</x-buttons.form>
                </li>
            </ul>
        </div>
    </nav>
</header>
