<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mk AGENCY | Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="login-body">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <img src="{{ $settings->site_logo ? asset('storage/' . $settings->site_logo) : asset('assets/images/logo.png') }}"
                    style="width: 50px; height: 50px; object-fit: cover; filter: brightness(0);" alt="logo">
            </div>
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to access your dashboard</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            @if (session('error'))
                <div style="color: red; margin-bottom: 1rem; text-align: left;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="form-group" style="text-align: left;">
                <label class="form-label">Email Address</label>
                <div class="search-bar" style="width: 100%;">
                    <i class="fa-regular fa-envelope" style="left: 1rem;"></i>
                    <input type="email" name="email" class="form-control" placeholder="admin@mkagency.com" required
                        style="padding-left: 2.5rem;" value="{{ old('email') }}">
                </div>
                @error('email')
                    <div style="color: red; font-size: 0.9em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="text-align: left;">
                <label class="form-label">Password</label>
                <div class="search-bar" style="width: 100%;">
                    <i class="fa-solid fa-lock" style="left: 1rem;"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required
                        style="padding-left: 2.5rem;">
                </div>
                @error('password')
                    <div style="color: red; font-size: 0.9em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" class="checkbox-label">Remember me</label>
                </div>
                {{-- <a href="#" style="font-size: 0.9rem; color: var(--accent); text-decoration: none;">Forgot
                    password?</a> --}}
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 1rem;">
                Sign In <i class="fa-solid fa-arrow-right"></i>
            </button>
            <span>Don't have an account? <a href="{{ route('register') }}"
                    style="color: var(--accent); text-decoration: none;">Sign Up</a></span>
        </form>

        {{-- <div class="auth-footer">
            Don't have an account? <a href="#">Contact Support</a>
        </div> --}}
    </div>
</body>

</html>
