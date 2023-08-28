<header class="container-fluid ps-4">
    <nav class="navbar navbar-expand navbar-light">
        <a class="navbar-brand fw-bold fs-1 text-white" href="{{ route('rides.index') }}"
            title="Return to ride listings">Ride-A-Long<sub class="orange">@UVA</sub></a>
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-2">
                <div class="dropdown nav-link">
                    <a class="nav-link" href="#" role="button" id="notificationsDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications Dropdown">
                        <i class="bi bi-car-front-fill" style="font-size: 2.5rem"></i>
                        <span id="notifsBadge" class="notification badge rounded-pill bg-danger">
                            <span id="numNotifs"></span>
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </a>
                    <ul id="notifs" class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                    </ul>
                </div>
            </li>
            <li class="nav-item me-2">
                <div class="dropdown nav-link">
                    <a class="nav-link" href="#" role="button" id="accountDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false" aria-label="Account Dropdown">
                        <i class="bi bi-person-fill" style="font-size: 2.5rem"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                        <li><a class="dropdown-item" href="#">My Rides</a></li>
                        <li><a class="dropdown-item" href="#">Messages</a></li>
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
    {{-- <?php require 'templates/notifications.php'; ?> --}}
</header>
