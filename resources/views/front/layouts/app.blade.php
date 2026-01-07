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
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="brand-icon">
                    <a href="{{ route('front.home') }}">
                        <img src="{{ $settings->site_logo ? asset('storage/' . $settings->site_logo) : asset('assets/images/logo.png') }}"
                            style="width: 50px; height: 50px; object-fit: cover; filter: brightness(0);" alt="logo">
                    </a>
                </div>
                <div class="brand-text ">{{ $settings->site_name }}</div>
            </div>
            <ul class="nav-links">
                <li class="nav-item">
                    <a href="{{ route('front.projects.index') }}"
                        class="nav-link {{ Route::is('front.projects.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-folder-open"></i>
                        <span>projects</span>
                    </a>
                </li>
            </ul>
            <div class="user-profile"
                style="position: relative; display: flex; align-items: center; justify-content: space-between;">
                <a href="{{ route('front.profile') }}">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ Auth::user()->getFirstMediaUrl('avatars') ? Auth::user()->getFirstMediaUrl('avatars') : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) . '&background=6366f1&color=fff' }}"
                            alt="{{ Auth::user()->first_name }}" class="user-avatar"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        <div class="user-info">
                            <h4>
                                <a href="{{ route('front.profile') }}"
                                    style="text-decoration: none; color: var(--text-secondary);">
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </a>
                            </h4>
                            <p style="font-size: 0.75rem;">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit"
                        style="background: none; border: none; cursor: pointer; color: var(--text-secondary); padding: 5px;"
                        title="Logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </aside>

        @yield('content')
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @yield('scripts')
</body>

</html>
