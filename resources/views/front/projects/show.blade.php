@extends('front.layouts.app')

@section('content')
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 style="font-size: 1.5rem; font-weight: 600;">Project Details</h2>
            </div>

            <div class="header-actions">
                <a href="{{ route('front.projects.index') }}" class="btn-outline" style="text-decoration: none;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Projects
                </a>
            </div>
        </header>

        <!-- Project Details -->
        <div class="settings-section animate-fade-in">
            <!-- Header with Title -->
            <div>
                <h1 style="font-size: 2rem; font-weight: 700;">{{ $project->getTranslation('name', 'en') ?? 'N/A' }}</h1>
                <p style="color: var(--text-secondary); font-size: 1.1rem;">
                    {{ $project->getTranslation('name', 'ar') ?? '' }}</p>
                <div style="margin-top: 1rem;">
                    @if ($project->active)
                        <span class="service-badge"
                            style="font-size: 1rem; padding: 0.5rem 1rem; background: rgba(34, 197, 94, 0.1); color: #22c55e;">Active</span>
                    @else
                        <span class="service-badge"
                            style="font-size: 1rem; padding: 0.5rem 1rem; background: rgba(239, 68, 68, 0.1); color: #ef4444;">Inactive</span>
                    @endif
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">

                {{-- Edit/Delete Actions for Owner --}}
                @if (auth()->id() === $project->user_id)
                    <div style="display: flex; gap: 1rem;">
                        <a href="{{ route('front.projects.edit', $project->id) }}" class="btn-primary"
                            style="background: #4ade80; border-color: transparent;">
                            <i class="fa-solid fa-pen"></i> Edit Project
                        </a>
                    </div>
                @endif
            </div>

            <div style="display: block;">
                <!-- Left Column: name & Media -->
                <div>
                    <!-- name -->
                    <div class="card"
                        style="margin-bottom: 2rem; padding: 1.5rem; border: 1px solid var(--border); border-radius: 12px;">
                        <h3 style="margin-bottom: 1rem;">name</h3>
                        <div style="line-height: 1.8; color: var(--text-primary);">
                            <h4 style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.5rem;">English</h4>
                            <p style="margin-bottom: 1.5rem;">
                                {{ $project->getTranslation('name', 'en') ?? 'No english name.' }}</p>

                            <h4 style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Arabic</h4>
                            <p style="direction: rtl;">{{ $project->getTranslation('name', 'ar') ?? 'لا يوجد اسم عربي.' }}
                            </p>
                        </div>
                    </div>



                    <!-- Gallery -->
                    <div class="card" style="padding: 1.5rem; border: 1px solid var(--border); border-radius: 12px;">
                        <h3 style="margin-bottom: 1rem;">Media Gallery</h3>
                        <div class="settings-section animate-fade-in">
                            <div class="settings-header">
                                <h3><i class="fa-solid fa-video"
                                        style="margin-right: 0.5rem; color: var(--accent-color);"></i>
                                    Project Videos</h3>
                            </div>
                            <div
                                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                                {{-- @dd($project->video_urls) --}}
                                @if ($project->video_urls)
                                    @foreach ($project->video_urls as $url)
                                        @php
                                            preg_match(
                                                '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
                                                $url,
                                                $matches,
                                            );
                                            $videoId = $matches[1] ?? null;
                                        @endphp
                                        @if ($videoId)
                                            <div style="border-radius: 12px; overflow: hidden; background: #000;">
                                                <iframe width="100%" height="200"
                                                    src="https://www.youtube.com/embed/{{ $videoId }}"
                                                    title="YouTube video player" frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <p style="color: var(--text-secondary);">No videos available.</p>
                                @endif
                            </div>
                        </div>
                        <!-- Photos -->
                        <h4 style="font-size: 1rem; margin-bottom: 1rem;">Photos
                            ({{ $project->getMedia('photos')->count() }})</h4>
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                            @forelse($project->getMedia('photos') as $media)
                                <div onclick="openMedia('image', '{{ $media->getUrl() }}')"
                                    style="cursor: pointer; position: relative; aspect-ratio: 1; overflow: hidden; border-radius: 8px;">
                                    <img src="{{ $media->getUrl() }}"
                                        style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                                        onmouseover="this.style.transform='scale(1.1)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                </div>
                            @empty
                                <p style="color: var(--text-secondary); grid-column: 1/-1;">No photos uploaded.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Media Lightbox Modal -->
    <div id="mediaModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 1000; padding: 2rem; align-items: center; justify-content: center;">
        <button onclick="closeModal()"
            style="position: absolute; top: 20px; right: 20px; background: none; border: none; color: white; font-size: 2rem; cursor: pointer; z-index: 1001;">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div id="modalContent"
            style="max-width: 90%; max-height: 90%; display: flex; align-items: center; justify-content: center;">
            <!-- Content injected by JS -->
        </div>
    </div>

    <script>
        function openMedia(type, url) {
            const modal = document.getElementById('mediaModal');
            const content = document.getElementById('modalContent');
            // ... rest of script

            modal.style.display = 'flex';
            content.innerHTML = ''; // Clear previous content

            if (type === 'image') {
                const img = document.createElement('img');
                img.src = url;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '90vh';
                img.style.objectFit = 'contain';
                content.appendChild(img);
            } else if (type === 'video') {
                const video = document.createElement('video');
                video.src = url;
                video.controls = true;
                video.autoplay = true;
                video.style.maxWidth = '100%';
                video.style.maxHeight = '90vh';
                content.appendChild(video);
            }

            // Close modal on background click
            modal.onclick = function(e) {
                if (e.target === modal) closeModal();
            }
        }

        function closeModal() {
            const modal = document.getElementById('mediaModal');
            const content = document.getElementById('modalContent');
            modal.style.display = 'none';
            content.innerHTML = ''; // Clear to stop video playback
        }

        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    </script>
@endsection
