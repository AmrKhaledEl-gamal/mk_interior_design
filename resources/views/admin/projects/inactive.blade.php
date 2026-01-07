@extends('admin.layout.layout')

@section('content')
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 style="font-size: 1.5rem; font-weight: 600;">Inactive Projects</h2>
            </div>

            {{-- Search or other actions can go here --}}
        </header>

        <!-- Projects Grid -->
        <div class="settings-section animate-fade-in">
            <div class="settings-header" style="margin-bottom: 1.5rem;">
                <h3>Review Inactive Projects</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Projects that are currently marked as inactive.
                </p>
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
                            <div
                                style="position: absolute; top: 10px; right: 10px; background: rgba(239, 68, 68, 0.9); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">
                                Inactive</div>
                        </div>
                        <div class="product-body">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div>
                                    <h3 class="product-title">
                                        {{ $project->getTranslation('name', 'en') ?? 'N/A' }}
                                    </h3>

                                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.2rem;">
                                        {{ $project->getTranslation('name', 'ar') }}
                                    </p>
                                </div>
                                <div style="display: flex; justify-content: end;gap: 1rem; align-items: start;">
                                    {{-- Actions can be added here later --}}
                                </div>
                            </div>

                            <div style="margin-top: auto; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fa-solid fa-photo-film" style="color: var(--text-secondary);"></i>
                                <span style="font-size: 0.8rem; color: var(--text-secondary);">
                                    {{ $project->getMedia('photos')->count() }} Photos •
                                    {{ $project->getMedia('videos')->count() }} Videos
                                </span>
                            </div>
                            <!-- User Info -->
                            @if ($project->user)
                                <div
                                    style="margin-top: 0.5rem; pt-2; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 0.5rem;">
                                    <img src="{{ $project->user->getFirstMediaUrl('avatars') }}"
                                        alt="{{ $project->user->first_name }}"
                                        style="width: 24px; height: 24px; border-radius: 50%;">
                                    <span
                                        style="font-size: 0.8rem; color: var(--text-secondary);">{{ $project->user->first_name }}
                                        {{ $project->user->last_name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">No inactive projects found.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pagination" style="margin-top: 2rem;">
                {{ $projects->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </main>
@endsection
