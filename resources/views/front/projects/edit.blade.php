@extends('front.layouts.app')

@section('content')
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
            <a href="{{ route('front.projects.index') }}" class="btn-outline" style="text-decoration:none;">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </header>

        <div class="settings-section">
            <form action="{{ route('front.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Project Titles --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Project Title (English)</label>
                        <input type="text" name="name[en]" class="form-control"
                            value="{{ old('name.en') ?? ($project->getTranslations('name')['en'] ?? '') }}"
                            placeholder="e.g. Skyline Tower" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="text-align:right;">Project Title (Arabic)</label>
                        <input type="text" name="name[ar]" class="form-control"
                            value="{{ old('name.ar') ?? ($project->getTranslations('name')['ar'] ?? '') }}"
                            placeholder="مثال: برج الأفق" dir="rtl">
                    </div>
                </div>

                {{-- Upload New Images --}}
                <div class="form-group">
                    <label class="form-label" for="images">Project Images</label>
                    <div
                        style="border:2px dashed var(--border); padding:2rem; text-align:center; border-radius:12px; background: rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-cloud-arrow-up"
                            style="font-size:2rem; color:var(--text-secondary); margin-bottom:1rem;"></i>
                        <p style="color:var(--text-secondary); margin-bottom:1rem;">Click or drag new images here to append
                            (Unlimited)</p>
                        <input type="file" name="photos[]" id="images" class="form-control" multiple accept="image/*"
                            onchange="previewImages(this)" style="opacity:1;position:static;height:auto;padding:10px;">
                        <div id="image-preview-container" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
                        </div>

                        {{-- Existing Images --}}
                        @if ($project->getMedia('photos')->count() > 0)
                            <div class="existing-images" style="margin-top:1.5rem;">
                                <label class="form-label">Current Images</label>
                                <div
                                    style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:1rem;">
                                    @foreach ($project->getMedia('photos') as $media)
                                        <div class="media-item" id="media-{{ $media->id }}" style="position:relative;">
                                            <img src="{{ $media->getUrl() }}"
                                                style="width:100%;height:100px;object-fit:cover;border-radius:8px;">
                                            <button type="button" class="delete-image-btn" data-id="{{ $media->id }}">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Existing Videos --}}
                @if ($project->getMedia('videos')->count() > 0)
                    <div class="form-group" style="margin-top:1.5rem;">
                        <label class="form-label">Current Videos</label>
                        <div class="media-grid">
                            @foreach ($project->getMedia('videos') as $media)
                                <div class="media-item video-item" id="media-{{ $media->id }}">
                                    <video preload="metadata">
                                        <source src="{{ $media->getUrl() }}" type="video/mp4">
                                    </video>
                                    <div class="play-overlay"><i class="fa-solid fa-play"></i></div>
                                    <button type="button" class="delete-image-btn" data-id="{{ $media->id }}">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Upload New Videos --}}
                <div class="form-group">
                    <label class="form-label">Add Project Videos</label>
                    <div
                        style="border:2px dashed var(--border); padding:2rem; text-align:center; border-radius:12px; background: rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-film"
                            style="font-size:2rem; color:var(--text-secondary); margin-bottom:1rem;"></i>
                        <p style="color:var(--text-secondary); margin-bottom:1rem;">Drag and drop your videos here</p>
                        <input type="file" name="videos[]" class="form-control" multiple accept="video/*">
                    </div>
                </div>

                {{-- Submit --}}
                <div style="display:flex;gap:1rem;margin-top:2rem;">
                    <button type="submit" class="btn-primary">Update Project</button>
                    <a href="{{ route('front.projects.index') }}" class="btn-outline" style="text-align:center;">Cancel</a>
                </div>

            </form>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: '{{ session('warning') ? 'warning' : 'error' }}',
                title: '{{ session('warning') ? 'تنبيه' : 'خطأ في رفع الملفات' }}',
                text: '{{ session('warning') ?? $errors->first() }}',
                confirmButtonText: 'حاول تاني'
            });
        </script>
    @endif
    <script>
        // Preview New Images
        function previewImages(input) {
            const container = document.getElementById('image-preview-container');
            container.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '8px';
                        container.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // Delete Image / Video via AJAX
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-image-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const mediaId = this.dataset.id;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This media will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ url('projects/media') }}/${mediaId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.status) {
                                        document.getElementById('media-' + mediaId)
                                            .remove();
                                        Swal.fire('Deleted!', 'Media has been deleted.',
                                            'success');
                                    } else {
                                        Swal.fire('Failed!', 'An error occurred.',
                                            'error');
                                    }
                                })
                                .catch(() => Swal.fire('Error!', 'An error occurred.',
                                    'error'));
                        }
                    });
                });
            });

            // Video Play / Pause on click
            document.querySelectorAll('.video-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    if (e.target.closest('.delete-image-btn')) return;
                    const video = this.querySelector('video');
                    if (video.paused) {
                        document.querySelectorAll('.video-item video').forEach(v => {
                            v.pause();
                            v.currentTime = 0;
                            v.closest('.video-item').classList.remove('playing');
                        });
                        video.play();
                        this.classList.add('playing');
                    } else {
                        video.pause();
                        this.classList.remove('playing');
                    }
                });
            });
        });
    </script>

    <style>
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 1rem;
        }

        .media-item {
            position: relative;
            width: 100%;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            background: #000;
            cursor: pointer;
        }

        .media-item img,
        .media-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .play-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .4);
            color: #fff;
            font-size: 24px;
            opacity: 1;
            transition: .3s;
        }

        .video-item.playing .play-overlay {
            opacity: 0;
            pointer-events: none;
        }

        .delete-image-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 38, 38, .9);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
        }
    </style>
@endsection
