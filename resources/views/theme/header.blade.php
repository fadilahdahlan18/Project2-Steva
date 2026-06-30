<!-- Topbar -->
<nav class="app-header navbar navbar-expand bg-body">

    <div class="container-fluid">

        <!-- Sidebar Toggle -->
        <ul class="navbar-nav">

            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Home</a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Contact</a>
            </li>

        </ul>

        <!-- Right Navbar -->
        <ul class="navbar-nav ms-auto">

            <!-- Search -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li>

            <!-- Notifications -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-bell-fill"></i>
                    <span class="badge text-bg-warning">5</span>
                </a>

                <div class="dropdown-menu dropdown-menu-end">
                    <span class="dropdown-item">5 Notifications</span>
                    <div class="dropdown-divider"></div>

                    <a href="#" class="dropdown-item">New Message</a>
                    <a href="#" class="dropdown-item">New User</a>
                    <a href="#" class="dropdown-item">Server Restarted</a>
                </div>
            </li>

            <!-- User Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">

                    <img src="{!! asset('img/user.png') !!}"
                         class="user-image rounded-circle shadow">

                    <span class="d-none d-md-inline">Admin</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li class="dropdown-item">
                        <a href="#">Profile</a>
                    </li>

                    <li class="dropdown-item">
                        <a href="#">Logout</a>
                    </li>

                </ul>
            </li>

        </ul>

    </div>

</nav>
<!-- End of Topbar -->
