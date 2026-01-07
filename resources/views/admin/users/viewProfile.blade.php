@extends('admin.layout.layout')


@section('content')
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 style="font-size: 1.5rem; font-weight: 600;">View User</h2>
            </div>

            <div class="header-actions">
                <a href="{{ route('admin.users.index') }}" class="btn-outline" style="text-decoration: none;">
                    <i class="fa-solid fa-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary" style="text-decoration: none;">
                    <i class="fa-solid fa-pen"></i> Edit User
                </a>
            </div>
        </header>

        <!-- User Profile Section -->
        <div class="settings-section animate-fade-in" style="margin-bottom: 2rem;">
            <div class="settings-header">
                <h3>User Profile</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Personal details and information.</p>
            </div>

            <div style="display: grid; grid-template-columns: 200px 1fr; gap: 2rem; align-items: start;">
                <div style="text-align: center;">
                    <img src="{{ $user->getFirstMediaUrl('avatars') }}" alt="{{ $user->first_name }}"
                        style="width: 100%; max-width: 200px; border-radius: 50%; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div>
                        <label
                            style="display: block; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">First
                            Name</label>
                        <div style="font-weight: 500; font-size: 1.1rem;">{{ $user->first_name }}</div>
                    </div>
                    <div>
                        <label
                            style="display: block; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">Last
                            Name</label>
                        <div style="font-weight: 500; font-size: 1.1rem;">{{ $user->last_name }}</div>
                    </div>
                    <div>
                        <label
                            style="display: block; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">Phone
                            1</label>
                        <div style="font-weight: 500; font-size: 1.1rem;">{{ $user->phone1 ?? '-' }}</div>
                    </div>
                    <div>
                        <label
                            style="display: block; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">Phone
                            2</label>
                        <div style="font-weight: 500; font-size: 1.1rem;">{{ $user->phone2 ?? '-' }}</div>
                    </div>
                    <div>
                        <label
                            style="display: block; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">Email
                            Address</label>
                        <div style="font-weight: 500; font-size: 1.1rem;">{{ $user->email }}</div>
                    </div>
                    <div>
                        <label
                            style="display: block; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">Status</label>
                        <div>
                            @if ($user->active)
                                <span
                                    style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">Active</span>
                            @else
                                <span
                                    style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div
                    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
                    @if ($user->portfolio_link)
                        <div>
                            <label
                                style="display: block; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">Portfolio
                                Link</label>
                            <div>
                                <a href="{{ $user->portfolio_link }}" target="_blank"
                                    style="color: var(--accent); text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-link"></i> {{ $user->portfolio_link }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($user->portfolio_pdf)
                        <div>
                            <label
                                style="display: block; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">Portfolio
                                PDF</label>
                            <div>
                                <a href="{{ asset('storage/' . $user->portfolio_pdf) }}" target="_blank"
                                    class="btn-outline"
                                    style="padding: 0.5rem 1rem; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-file-pdf"></i> Download PDF
                                </a>
                            </div>
                        </div>
                    @endif
                    <div style="grid-column: 1 / -1;">
                        <label
                            style="display: block; color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">About
                            Me</label>
                        <div style="line-height: 1.6; color: var(--text-primary);">
                            {{ $user->description ?? 'No description provided.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Projects Section -->
        <div class="settings-section animate-fade-in">
            <div class="settings-header" style="margin-bottom: 1.5rem;">
                <h3>Assigned Projects</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Projects monitored or managed by this user.</p>
            </div>


            <div class="product-grid">
                @forelse($projects as $project)
                    <div class="product-card">
                        <div style="position: relative; overflow: hidden;">
                            <a href="{{ route('admin.projects.show', $project) }}" style="display: block;">
                                <img src="{{ $project->getFirstMediaUrl('photos') ?: 'https://source.unsplash.com/random/400x300?building' }}"
                                    alt="{{ $project->name['en'] ?? '' }}" class="product-image">
                            </a>
                            <div class="service-badge">{{ $project->category }}</div>
                            @if ($project->active)
                                <div
                                    style="position: absolute; top: 10px; right: 10px; background: rgba(34, 197, 94, 0.9); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">
                                    Active</div>
                            @else
                                <div
                                    style="position: absolute; top: 10px; right: 10px; background: rgba(239, 68, 68, 0.9); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">
                                    Inactive</div>
                            @endif
                        </div>
                        <div class="product-body">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div>
                                    <h3 class="product-title">{{ $project->getTranslation('name', 'en') ?? 'N/A' }}</h3>
                                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.2rem;">
                                        {{ $project->getTranslation('name', 'ar') ?? '' }}
                                    </p>
                                </div>
                                <div style="display: flex; justify-content: end;gap: 1rem; align-items: start;">
                                    {{-- <a href="{{ route('admin.projects.show', $project->id) }}" class="action-btn" --}}
                                    {{-- style="color: #4ade80;"><i class="fa-solid fa-pen"></i></a> --}}
                                </div>
                            </div>

                            <div
                                style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                                <div style="display: flex; gap: 1rem;">
                                    <div
                                        style="display: flex; align-items: center; gap: 0.3rem; color: var(--text-secondary); font-size: 0.85rem;">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>{{ $project->views }}</span>
                                    </div>
                                    <button
                                        style="background: none; border: none; cursor: default; display: flex; align-items: center; gap: 0.3rem; color: var(--text-secondary); font-size: 0.85rem; padding: 0;">
                                        <i class="fa-regular fa-heart"></i>
                                        <span class="likes-count">{{ $project->likes }}</span>
                                    </button>
                                </div>

                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fa-solid fa-photo-film" style="color: var(--text-secondary);"></i>
                                    <span style="font-size: 0.8rem; color: var(--text-secondary);">
                                        {{ $project->getMedia('photos')->count() }} •
                                        {{ $project->getMedia('videos')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">No projects found.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </main>
@endsection
