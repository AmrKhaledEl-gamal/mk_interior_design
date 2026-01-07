<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->site_name }} | Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-body">
    <div class="auth-card" style="margin-top: 2rem; margin-bottom: 2rem;">
        <div class="auth-header">
            <div class="auth-icon">
                <img src="{{ $settings->site_logo ? asset('storage/' . $settings->site_logo) : asset('assets/images/logo.png') }}"
                    style="width: 50px; height: 50px; object-fit: cover; filter: brightness(0);" alt="logo">
            </div>
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Sign up to get started</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <!-- First Name -->
                <div class="form-group" style="text-align: left;">
                    <label class="form-label">First Name</label>
                    <div class="search-bar" style="width: 100%;">
                        <i class="fa-regular fa-user" style="left: 1rem;"></i>
                        <input type="text" class="form-control" name="first_name" placeholder="John" required
                            style="padding-left: 2.5rem;">
                    </div>
                </div>
                <!-- Second Name -->
                <div class="form-group" style="text-align: left;">
                    <label class="form-label">Second Name</label>
                    <div class="search-bar" style="width: 100%;">
                        <i class="fa-regular fa-user" style="left: 1rem;"></i>
                        <input type="text" class="form-control" name="last_name" placeholder="Doe" required
                            style="padding-left: 2.5rem;">
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group" style="text-align: left;">
                <label class="form-label">Email Address</label>
                <div class="search-bar" style="width: 100%;">
                    <i class="fa-regular fa-envelope" style="left: 1rem;"></i>
                    <input type="email" class="form-control" name="email" placeholder="john.doe@example.com"
                        required style="padding-left: 2.5rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <!-- Phone 1 -->
                <div class="form-group" style="text-align: left;">
                    <label class="form-label">Phone 1</label>
                    <div class="search-bar" style="width: 100%;">
                        <i class="fa-solid fa-phone" style="left: 1rem;"></i>
                        <input type="tel" class="form-control" name="phone1" placeholder="+1234567890" required
                            style="padding-left: 2.5rem;">
                    </div>
                </div>

                <!-- Phone 2 -->
                <div class="form-group" style="text-align: left;">
                    <label class="form-label">Phone 2</label>
                    <div class="search-bar" style="width: 100%;">
                        <i class="fa-solid fa-phone" style="left: 1rem;"></i>
                        <input type="tel" class="form-control" name="phone2" placeholder="+0987654321"
                            style="padding-left: 2.5rem;">
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group" style="text-align: left;">
                <label class="form-label">Password</label>
                <div class="search-bar" style="width: 100%;">
                    <i class="fa-solid fa-lock" style="left: 1rem;"></i>
                    <input type="password" class="form-control" name="password" placeholder="••••••••" required
                        style="padding-left: 2.5rem;">
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group" style="text-align: left;">
                <label class="form-label">Confirm Password</label>
                <div class="search-bar" style="width: 100%;">
                    <i class="fa-solid fa-lock" style="left: 1rem;"></i>
                    <input type="password" class="form-control" name="password_confirmation" placeholder="••••••••"
                        required style="padding-left: 2.5rem;">
                </div>
            </div>

            <button type="submit" class="btn-primary"
                style="width: 100%; justify-content: center; padding: 1rem; margin-top: 1rem;">
                Register <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
</body>

</html>
