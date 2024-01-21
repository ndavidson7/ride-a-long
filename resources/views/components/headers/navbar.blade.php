<header
    class="min-h-20 flex items-center border-b-8 border-blue-900 bg-gradient-to-br from-blue-900 via-blue-500 to-blue-900 p-4">
    <nav class="flex flex-1 flex-wrap items-center gap-2">
        <a class="me-auto text-4xl font-black text-white" href="{{ route('rides.index') }}"
            title="Return to ride listings">{{ config('app.name') }}</a>
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
            <!-- Button -->
            <button class="flex items-center gap-2 rounded-md bg-white px-3 py-2 shadow" type="button" x-ref="button"
                x-on:click="toggle()" :aria-expanded="open" :aria-controls="$id('dropdown-button')">
                @if ($pfp = auth()->user()->fetchFirstMedia())
                    <img class="h-5 w-5" src="{{ $pfp['file_url'] }}" alt="Profile picture">
                @else
                    <x-fas-circle-user class="h-5 w-5"></x-fas-circle-user>
                @endif

                <x-fas-chevron-down class="h-4 w-4 duration-200 ease-out" ::class="open && 'rotate-180'" />
            </button>

            <!-- Panel -->
            <div class="w-40 rounded-md bg-white shadow-md" x-cloak x-anchor.bottom-end="$refs.button" x-ref="panel"
                x-show="open" x-transition.origin.top.right x-on:click.outside="close($refs.button)"
                :id="$id('dropdown-button')">
                <a class="flex w-full items-center gap-2 rounded-t-md px-4 py-2.5 text-left text-sm hover:bg-gray-50 disabled:text-gray-500"
                    href="{{ route('users.show') }}">Profile</a>

                <a class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm hover:bg-gray-50 disabled:text-gray-500"
                    href="{{ route('rides.index', ['my-rides' => 1]) }}">My Rides</a>

                {{-- <a class="dropdown-item" href="{{ route('requests.index') }}">Requests</a> --}}

                <a class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm hover:bg-gray-50 disabled:text-gray-500"
                    href="#">Messages</a>

                <a class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm hover:bg-gray-50 disabled:text-gray-500"
                    href="{{ route('alerts.index') }}">Alerts</a>

                <a class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm hover:bg-gray-50 disabled:text-gray-500"
                    href="{{ route('settings.index') }}">Settings</a>

                <x-buttons.form
                    class="flex w-full items-center gap-2 rounded-b-md px-4 py-2.5 text-left text-sm text-red-500 hover:bg-gray-50 disabled:text-gray-500"
                    action="{{ route('sessions.destroy') }}">Sign out</x-buttons.form>
            </div>
        </div>
        {{-- <ul class="navbar-nav align-items-center ms-auto">
            <li class="nav-item me-2">
                <div class="dropdown nav-link">
                    <a class="nav-link" id="notificationsDropdown" data-bs-toggle="dropdown" href="#"
                        role="button" aria-expanded="false" aria-label="Notifications Dropdown">
                        <i class="bi bi-bell-fill" style="font-size: 2rem"></i>
                        <span class="notification badge rounded-pill bg-danger" id="notifsBadge">
                            <span id="numNotifs">{{ auth()->user()->unreadNotifications()->count() }}</span>
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" id="notifs" aria-labelledby="notificationsDropdown">
                        @foreach (auth()->user()->notifications as $notification)
                            <li class="d-flex justify-content-center align-items-center">
                                <a class='dropdown-item d-flex align-items-center'
                                    href="{{ route('notifications.show', $notification['id']) }}">
                                    @if ($notification['read_at'] === null)
                                        <i class="bi bi-circle-fill text-danger me-2" style="font-size: 0.3rem"></i>
                                    @endif
                                    {{ $notification['data']['message'] }}
                                    <small
                                        class="text-body-secondary ms-2">{{ $notification->created_at->diffForHumans() }}</small>
                                </a>
                                <form action="{{ route('notifications.destroy', $notification['id']) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="dropdown-item" type="submit"><i
                                            class="bi bi-x-lg text-body-secondary"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="nav-item me-2">
                <div class="dropdown nav-link">
                    <a class="nav-link" id="accountDropdown" data-bs-toggle="dropdown" href="#" role="button"
                        aria-expanded="false" aria-label="Account Dropdown">
                        @if ($pfp = auth()->user()->fetchFirstMedia())
                            <img src="{{ $pfp['file_url'] }}" alt="Profile picture" style="height:3em; width:auto;">
                        @else
                            <i class="bi bi-person-circle" style="font-size: 2.5rem"></i>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                        <li><a class="dropdown-item" href="{{ route('users.show') }}">Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('rides.index', ['my-rides' => 1]) }}">My Rides</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('requests.index') }}">Requests</a></li>
                        <li><a class="dropdown-item" href="#">Messages</a></li>
                        <li><a class="dropdown-item" href="{{ route('alerts.index') }}">Alerts</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="/signout" method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="dropdown-item" type="submit">Sign Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul> --}}
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
