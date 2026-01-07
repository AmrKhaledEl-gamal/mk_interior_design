@extends('front.layouts.app')

@section('content')
    <main class="main-content">
        <div class="settings-header">
            <h3 class="settings-title">My Profile</h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success"
                style="background: rgba(16, 185, 129, 0.1); color: #34d399; padding: 1rem; margin-bottom: 2rem; border-radius: 8px; border: 1px solid rgba(16, 185, 129, 0.2);">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('front.profile.update') }}" method="POST" enctype="multipart/form-data"
            class="settings-section">
            @csrf

            <div style="display: flex; gap: 3rem; align-items: flex-start; flex-wrap: wrap;">
                <!-- Photo Section -->
                <div style="flex-shrink: 0; text-align: center;">
                    <div style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                        @if ($user->getFirstMediaUrl('avatars'))
                            <img src="{{ $user->getFirstMediaUrl('avatars') }}" alt="Profile Photo"
                                style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent);">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->first_name . ' ' . $user->last_name) }}&background=6366f1&color=fff"
                                alt="Profile Photo"
                                style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent);">
                        @endif
                        <label for="photo"
                            style="position: absolute; bottom: 0; right: 0; background: var(--accent); color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid var(--bg-card);">
                            <i class="fa-solid fa-camera" style="font-size: 0.8rem;"></i>
                        </label>
                        <input type="file" name="photo" id="photo" style="display: none;">
                    </div>
                    <p style="margin-top: 1rem; color: var(--text-secondary); font-size: 0.875rem;">Allowed *.jpeg, *.jpg,
                        *.png,
                        *.gif</p>
                </div>

                <!-- Form Fields -->
                <div style="flex-grow: 1; min-width: 300px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <!-- First Name -->
                        <div class="form-group">
                            <label class="form-label ">First Name</label>
                            <div class="search-bar" style="width: 100%;">
                                <i class="fa-regular fa-user"></i>
                                <input type="text" class="form-control" name="first_name"
                                    value="{{ old('first_name', $user->first_name) }}" placeholder="John">
                            </div>
                            @error('first_name')
                                <span
                                    style="color: var(--danger); font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <div class="search-bar" style="width: 100%;">
                                <i class="fa-regular fa-user"></i>
                                <input type="text" class="form-control" name="last_name"
                                    value="{{ old('last_name', $user->last_name) }}" placeholder="Doe">
                            </div>
                            @error('last_name')
                                <span
                                    style="color: var(--danger); font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <div class="search-bar" style="width: 100%;">
                                <i class="fa-regular fa-envelope"></i>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $user->email) }}" placeholder="john@example.com">
                            </div>
                            @error('email')
                                <span
                                    style="color: var(--danger); font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone 1 -->
                        <div class="form-group">
                            <label class="form-label">Phone 1</label>
                            <div class="search-bar" style="width: 100%;">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" class="form-control" name="phone1"
                                    value="{{ old('phone1', $user->phone1) }}" placeholder="+1 234 567 890">
                            </div>
                            @error('phone1')
                                <span
                                    style="color: var(--danger); font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone 2 -->
                        <div class="form-group">
                            <label class="form-label">Phone 2</label>
                            <div class="search-bar" style="width: 100%;">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" class="form-control" name="phone2"
                                    value="{{ old('phone2', $user->phone2) }}" placeholder="+1 987 654 321">
                            </div>
                            @error('phone2')
                                <span
                                    style="color: var(--danger); font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label class="form-label">About Me</label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Tell us a little about yourself...">{{ old('description', $user->description) }}</textarea>
                        @error('description')
                            <span
                                style="color: var(--danger); font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div style="text-align: right; margin-top: 2rem;">
                        <button type="submit" class="btn-primary">
                            <span>Save Changes</span>
                            <i class="fa-solid fa-check"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
