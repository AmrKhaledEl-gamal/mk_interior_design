@extends('front.layouts.app')

@section('content')
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.2rem;">Dashboard</h2>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Welcome back,
                        {{ auth()->user()->first_name }}</p>
                </div>
            </div>

            <div class="header-actions">
                <a href="{{ route('front.projects.create') }}" class="btn-primary" style="text-decoration: none;">
                    <i class="fa-solid fa-plus"></i> New Project
                </a>
            </div>
        </header>

        <!-- Stats Grid -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <!-- Total Projects -->
            <div class="card"
                style="padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border); background: var(--bg-secondary);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <div>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.3rem;">Total Projects
                        </p>
                        <h3 style="font-size: 1.8rem; font-weight: 700;">{{ $stats['total_projects'] }}</h3>
                    </div>
                    <div
                        style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.1); color: #6366f1; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-folder"></i>
                    </div>
                </div>
                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                    All projects in your portfolio
                </div>
            </div>

            <!-- Active Projects -->
            <div class="card"
                style="padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border); background: var(--bg-secondary);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <div>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.3rem;">Active</p>
                        <h3 style="font-size: 1.8rem; font-weight: 700;">{{ $stats['active_projects'] }}</h3>
                    </div>
                    <div
                        style="width: 48px; height: 48px; background: rgba(34, 197, 94, 0.1); color: #22c55e; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                </div>
                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                    Visible on the platform
                </div>
            </div>

            <!-- Inactive Projects -->
            <div class="card"
                style="padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border); background: var(--bg-secondary);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <div>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.3rem;">Inactive</p>
                        <h3 style="font-size: 1.8rem; font-weight: 700;">{{ $stats['inactive_projects'] }}</h3>
                    </div>
                    <div
                        style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-eye-slash"></i>
                    </div>
                </div>
                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                    Hidden or Pending
                </div>
            </div>

            <!-- Total Views -->
            <div class="card"
                style="padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border); background: var(--bg-secondary);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <div>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.3rem;">Total Views</p>
                        <h3 style="font-size: 1.8rem; font-weight: 700;">{{ $stats['total_views'] }}</h3>
                    </div>
                    <div
                        style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                </div>
                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                    Clicks across all projects
                </div>
            </div>
        </div>

        <!-- Recent Projects Section -->
        <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: end;">
            <h3 style="font-size: 1.25rem; font-weight: 600;">Recent Projects</h3>
            <a href="{{ route('front.projects.index') }}"
                style="color: #6366f1; text-decoration: none; font-size: 0.95rem; font-weight: 500;">View All <i
                    class="fa-solid fa-arrow-right" style="margin-left: 0.2rem; font-size: 0.8rem;"></i></a>
        </div>

        <div class="product-grid">
            @forelse($recentProjects as $project)
                <div class="product-card">
                    <div style="position: relative; overflow: hidden;">
                        <a href="{{ route('front.projects.show', $project) }}" style="display: block;">
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
                                <h3 class="product-title">
                                    {{ $project->getTranslation('name', 'en') ?? 'N/A' }}
                                </h3>

                                <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.2rem;">
                                    {{ $project->getTranslation('name', 'ar') }}
                                </p>
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
                <div class="card"
                    style="grid-column: 1/-1; text-align: center; padding: 3rem; border: 1px dashed var(--border); background: var(--bg-secondary);">
                    <div
                        style="width: 60px; height: 60px; background: var(--bg-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="fa-solid fa-folder-plus" style="font-size: 1.5rem; color: var(--text-secondary);"></i>
                    </div>
                    <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;">No projects yet</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Start building your portfolio by adding
                        your first project.</p>
                    <a href="{{ route('front.projects.create') }}" class="btn-primary"
                        style="text-decoration: none;">Create Project</a>
                </div>
            @endforelse
        </div>
    </main>
@endsection
