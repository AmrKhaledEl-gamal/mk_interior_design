@extends('admin.layout.layout')

@section('content')
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left" style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 style="font-size: 1.5rem; font-weight: 600;">Add New User</h2>
            </div>

            <div class="header-actions">
                <a href="{{ route('admin.users.index') }}" class="btn-outline" style="text-decoration: none;">
                    <i class="fa-solid fa-arrow-left"></i> Back to List
                </a>
            </div>
        </header>

        <!-- User Form Section -->
        <div class="settings-section animate-fade-in">
            <div class="settings-header">
                <h3>User Details</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">
                    Fill in the information to create a new user account.
                </p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">

                    <!-- Avatar -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        @error('photo')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" placeholder="John"
                            value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" placeholder="Doe"
                            value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone1 -->
                    <div class="form-group">
                        <label class="form-label">Phone 1</label>
                        <input type="text" name="phone1" class="form-control" placeholder="+1234567890"
                            value="{{ old('phone1') }}">
                        @error('phone1')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone2 -->
                    <div class="form-group">
                        <label class="form-label">Phone 2</label>
                        <input type="text" name="phone2" class="form-control" placeholder="+1234567890"
                            value="{{ old('phone2') }}">
                        @error('phone2')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 2rem;">
                            <input type="checkbox" id="active" name="active" value="1"
                                {{ old('active', true) ? 'checked' : '' }} style="width: auto; margin: 0;">
                            <label for="active" style="margin: 0; cursor: pointer;">Active Status (Can Login)</label>
                        </div>

                        <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
                            <input type="checkbox" id="is_approved" name="is_approved" value="1"
                                {{ old('is_approved', false) ? 'checked' : '' }} style="width: auto; margin: 0;">
                            <label for="is_approved" style="margin: 0; cursor: pointer;">Dashboard Access (Is
                                Approved)</label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" placeholder="User bio or description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        @error('password')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••"
                            required>
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="form-actions"
                    style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                    <a href="{{ route('admin.users.index') }}" class="btn-outline"
                        style="text-decoration: none;">Cancel</a>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-check"></i> Create User
                    </button>
                </div>

            </form>
        </div>

    </main>

    <!-- Optional: unified error style -->
    <style>
        .error-msg {
            color: red;
            font-size: 0.8rem;
            margin-top: 5px;
        }
    </style>
@endsection
