<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->site_name }} | Analytics Dashboard</title>
    <!-- Fonts -->
    <link type="image/x-icon"
        href="{{ $settings->site_favicon ? asset('storage/' . $settings->site_favicon) : asset('assets/images/favicon.ico') }}"
        rel="icon" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="brand-icon">
                    <img src="{{ $settings->site_logo ? asset('storage/' . $settings->site_logo) : asset('assets/images/logo.png') }}"
                        style="width: 50px; height: 50px; object-fit: cover; filter: brightness(0);" alt="logo">
                </div>
                <div class="brand-text">{{ $settings->site_name }}</div>
            </div>

            <ul class="nav-links">
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}"
                        class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('front.home') }}" target="_blank">
                        <i class="fa-solid fa-globe"></i>
                        <span>View Website</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.projects.inactive') }}"
                        class="{{ request()->routeIs('admin.projects.inactive') ? 'active' : '' }}">
                        <i class="fa-solid fa-ban"></i>
                        <span>Inactive Projects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.*') && !request()->routeIs('admin.users.pending') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.pending') }}"
                        class="{{ request()->routeIs('admin.users.pending') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-clock"></i>
                        <span>Pending Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.general.index') }}"
                        class="{{ request()->routeIs('admin.settings.general.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-gear"></i>
                        <span>General Settings</span>
                    </a>
                </li>
            </ul>

            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=6366f1&color=fff" alt="Admin"
                    class="user-avatar">
                <div class="user-info">
                    <h4>Admin User</h4>
                    <p>Super Admin</p>
                </div>
            </div>
        </aside>

        @yield('content')
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @yield('scripts')
</body>

</html>
