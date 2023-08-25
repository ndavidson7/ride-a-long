<header class="container-fluid ps-4">
    <nav class="navbar navbar-expand navbar-light">
        <a class="navbar-brand fw-bold fs-1 text-white" href="/rides">Ride-A-Long<sub class="orange">@UVA</sub></a>
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-2">
                <div class="dropdown nav-link fs-1">
                    <a class="nav-link fs-2" href="#" role="button" id="notificationsDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications Dropdown">
                        <i class="fa-solid fa-car"></i>
                        <span id="notifsBadge" class="notification badge rounded-pill bg-danger">
                            <span id="numNotifs"></span>
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </a>
                    <ul id="notifs" class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <div class="dropdown nav-link fs-1">
                    <a class="nav-link" href="#" role="button" id="accountDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false" aria-label="Account Dropdown">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                        <li><a class="dropdown-item" href="/profile">Profile</a></li>
                        <li><a class="dropdown-item" href="/myrides">My Rides</a></li>
                        <li><a class="dropdown-item" href="/messages">Messages</a></li>
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
