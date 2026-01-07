@extends('admin.layout.layout')

@section('content')
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left" style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 style="font-size: 1.5rem; font-weight: 600;">Edit User</h2>
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

            <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">

                    <!-- Avatar -->
                    <div class="form-group" style="grid-column: 1 / -1; display: flex; align-items: center; gap: 1rem;">
                        <div style="flex-shrink: 0;">
                            <img src="{{ $user->getFirstMediaUrl('avatars') ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name) . '&background=random' }}"
                                alt="Current Avatar"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);">
                        </div>
                        <div style="flex-grow: 1;">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                            @error('photo')
                                <div class="error-msg">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- First Name -->
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" placeholder="John"
                            value="{{ old('first_name', $user->first_name) }}" required>
                        @error('first_name')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" placeholder="Doe"
                            value="{{ old('last_name', $user->last_name) }}" required>
                        @error('last_name')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone1 -->
                    <div class="form-group">
                        <label class="form-label">Phone1</label>
                        <input type="text" name="phone1" class="form-control" placeholder="+200000000000"
                            value="{{ old('phone1', $user->phone1) }}">
                        @error('phone1')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone2 -->
                    <div class="form-group">
                        <label class="form-label">Phone2</label>
                        <input type="text" name="phone2" class="form-control" placeholder="+200000000000"
                            value="{{ old('phone2', $user->phone2) }}">
                        @error('phone2')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 2rem;">
                            <input type="checkbox" id="active" name="active" value="1"
                                {{ old('active', $user->active) ? 'checked' : '' }} style="width: auto; margin: 0;">
                            <label for="active" style="margin: 0; cursor: pointer;">Active Status (Can Login)</label>
                        </div>

                        <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
                            <input type="checkbox" id="is_approved" name="is_approved" value="1"
                                {{ old('is_approved', $user->is_approved) ? 'checked' : '' }}
                                style="width: auto; margin: 0;">
                            <label for="is_approved" style="margin: 0; cursor: pointer;">Dashboard Access (Is
                                Approved)</label>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" placeholder="User bio or description...">{{ old('description', $user->description) }}</textarea>
                        @error('description')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>



                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password (Leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                        @error('password')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="form-actions"
                    style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                    <a href="{{ route('admin.users.index') }}" class="btn-outline"
                        style="text-decoration: none;">Cancel</a>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-check"></i> Update User
                    </button>
                </div>
            </form>
        </div>

    </main>

    <!-- Optional: custom error style -->
    <style>
        .error-msg {
            color: red;
            font-size: 0.8rem;
            margin-top: 5px;
        }
    </style>
@endsection
