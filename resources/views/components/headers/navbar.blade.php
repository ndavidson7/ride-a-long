<header class="container-fluid ps-4">
    <nav class="navbar navbar-expand navbar-light">
        <a class="navbar-brand fw-bold fs-1 text-white" href="{{ route('rides.index') }}"
            title="Return to ride listings">Ride-A-Long</a>
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-2">
                <div class="dropdown nav-link">
                    <a class="nav-link" href="#" role="button" id="notificationsDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false" aria-label="Notifications Dropdown">
                        <i class="bi bi-bell-fill" style="font-size: 2rem"></i>
                        <span id="notifsBadge" class="notification badge rounded-pill bg-danger">
                            <span id="numNotifs">{{ auth()->user()->unreadNotifications()->count() }}</span>
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </a>
                    <ul id="notifs" class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
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
                                    <button type="submit" class="dropdown-item"><i
                                            class="bi bi-x-lg text-body-secondary"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
            <li class="nav-item me-2">
                <div class="dropdown nav-link">
                    <a class="nav-link" href="#" role="button" id="accountDropdown" data-bs-toggle="dropdown"
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
                        {{-- <li><a class="dropdown-item" href="{{ route('requests.index') }}">Requests</a></li> --}}
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
                                <button type="submit" class="dropdown-item">Sign Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
    <template id="notif-delete-form">
        <form action="" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="dropdown-item"><i class="bi bi-x-lg text-body-secondary"></i></button>
        </form>
    </template>
    @vite('resources/js/notifications.js')
</header>
