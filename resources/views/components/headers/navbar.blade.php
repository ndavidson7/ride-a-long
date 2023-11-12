<header class="container-fluid ps-4">
    <nav class="navbar navbar-expand navbar-light">
        <a class="navbar-brand fw-bold fs-1 text-white" href="{{ route('rides.index') }}"
            title="Return to ride listings">Ride-A-Long<sub class="orange">@UVA</sub></a>
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-2">
                <div class="dropdown nav-link">
                    <a class="nav-link" href="#" role="button" id="notificationsDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications Dropdown">
                        <i class="bi bi-bell-fill" style="font-size: 2rem"></i>
                        <span id="notifsBadge" class="notification badge rounded-pill bg-danger">
                            <span id="numNotifs">{{ auth()->user()->unreadNotifications()->count() }}</span>
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </a>
                    <ul id="notifs" class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                        @foreach (auth()->user()->notifications as $notification)
                            <li class="d-flex justify-content-center align-items-center">
                                @if ($notification['read_at'] === null)
                                    <i class="bi bi-circle-fill text-danger ms-3" style="font-size: 0.5rem"></i>
                                @endif
                                <a class='dropdown-item' href="{{ route('notifications.show', $notification['id']) }}">
                                    @switch($notification['type'])
                                        @case('App\\Notifications\\RequestCreated')
                                            {{ $notification['data']['user']['first_name'] }}
                                            {{ $notification['data']['user']['last_name'] }} requested to join your ride!
                                        @break

                                        @case('App\\Notifications\\RequestUpdated')
                                            {{ $notification['data']['driver']['first_name'] }}
                                            {{ $notification['data']['driver']['last_name'] }}
                                            {{ $notification['data']['response'] == 1 ? 'accepted' : 'declined' }} your
                                            request!
                                        @break

                                        @case('App\\Notifications\\RideUserDestroyed')
                                            {{ $notification['data']['user']['first_name'] }}
                                            {{ $notification['data']['user']['last_name'] }}
                                            left your ride from {{ $notification['data']['ride']['origin']['city'] }} to
                                            {{ $notification['data']['ride']['destination']['city'] }}!
                                        @break
                                    @endswitch

                                    <small class="text-muted" id="notif-time-ago"></small>
                                </a>
                                <form action="{{ route('notifications.destroy', $notification['id']) }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i
                                            class="bi bi-x-lg text-muted"></i></button>
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
                            <img src="{{ $pfp['file_url'] }}" alt="Profile picture" class="rounded-circle shadow-lg"
                                style="height:3em; width:auto;">
                        @else
                            <i class="bi bi-person-circle" style="font-size: 2.5rem"></i>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('rides.index', ['my-rides' => 1]) }}">My Rides</a>
                        </li>
                        {{-- <li><a class="dropdown-item" href="{{ route('requests.index') }}">Requests</a></li> --}}
                        <li><a class="dropdown-item" href="#">Messages</a></li>
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
            <button type="submit" class="dropdown-item"><i class="bi bi-x-lg text-muted"></i></button>
        </form>
    </template>
    @vite('resources/js/notifications.js')
</header>
