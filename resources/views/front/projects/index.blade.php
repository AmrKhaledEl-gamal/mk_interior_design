@extends('front.layouts.app')
@section('content')
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            {{-- <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 style="font-size: 1.5rem; font-weight: 600;">Projects</h2>
            </div> --}}

            <div class="header-actions">
                <a href="{{ route('front.projects.create') }}" class="btn-primary" style="text-decoration: none;">
                    <i class="fa-solid fa-plus"></i> Add Project
                </a>
                {{-- <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search projects...">
                </div> --}}
            </div>
        </header>

        <div class="product-grid">
            @forelse($projects as $project)
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
                            <div style="display: flex; justify-content: end;gap: 1rem; align-items: start;">
                                <a href="{{ route('front.projects.edit', $project->id) }}" class="action-btn"
                                    style="color: #4ade80;"><i class="fa-solid fa-pen"></i></a>
                                <form action="{{ route('front.projects.destroy', $project->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn" style="color: #ef4444;"><i
                                            class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        <p
                            style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem; line-height: 1.5; margin-top: 0.5rem;">
                            {{ Str::limit($project->description['en'] ?? '', 100) }}
                        </p>
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
                                    {{ is_array($project->video_urls) ? count($project->video_urls) : 0 }}
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

        <!-- Pagination -->
        <div class="pagination">
            {{ $projects->links('pagination::bootstrap-4') }}
        </div>
    </main>

    <script>
        // Like functionality removed from this frontend as per request.
    </script>
@endsection
