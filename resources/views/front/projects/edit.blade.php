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
            <form id="projectForm" action="{{ route('front.projects.update', $project->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Project Titles -->
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

                <!-- Upload New Images -->
                <div class="form-group">
                    <label class="form-label">Project Photos (Upload Multiple)</label>
                    <div
                        style="border:2px dashed var(--border); padding:2rem; text-align:center; border-radius:12px; background: rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-images"
                            style="font-size:2rem; color:var(--text-secondary); margin-bottom:1rem;"></i>
                        <p style="color:var(--text-secondary); margin-bottom:1rem;">Click or drag new images here to append
                        </p>
                        <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/*">
                        <div id="image-preview-container" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
                        </div>

                        <!-- Existing Images -->
                        @if ($project->getMedia('photos')->count() > 0)
                            <div class="existing-images" style="margin-top:1.5rem;">
                                <label class="form-label">Current Images</label>
                                <div
                                    style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:1rem;">
                                    @foreach ($project->getMedia('photos') as $media)
                                        <div class="media-item" id="media-{{ $media->id }}" style="position:relative;">
                                            <img src="{{ $media->getUrl() }}"
                                                style="width:100%;height:100px;object-fit:cover;border-radius:8px;">
                                            <button type="button" class="delete-image-btn" data-id="{{ $media->id }}"
                                                style="position:absolute;top:5px;right:5px;background:rgba(220,38,38,.9);color:#fff;border:none;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:2;">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Upload New Videos -->
                {{-- <div class="form-group">
                    <label class="form-label">Project Videos (Upload Multiple)</label>
                    <div
                        style="border:2px dashed var(--border); padding:2rem; text-align:center; border-radius:12px; background: rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-film"
                            style="font-size:2rem; color:var(--text-secondary); margin-bottom:1rem;"></i>
                        <p style="color:var(--text-secondary); margin-bottom:1rem;">Drag and drop your videos here</p>
                        <input type="file" id="videos" name="videos[]" class="form-control" multiple accept="video/*">
                    </div>

                    <!-- Existing Videos -->
                    @if ($project->getMedia('videos')->count() > 0)
                        <div class="form-group" style="margin-top:1.5rem;">
                            <label class="form-label">Current Videos</label>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:1rem;">
                                @foreach ($project->getMedia('videos') as $media)
                                    <div class="media-item video-item" id="media-{{ $media->id }}"
                                        style="position:relative;">
                                        <video preload="metadata" style="width:100%;height:100px;object-fit:cover;">
                                            <source src="{{ $media->getUrl() }}" type="video/mp4">
                                        </video>
                                        <div class="play-overlay"
                                            style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.4);color:#fff;font-size:24px;">
                                            <i class="fa-solid fa-play"></i>
                                        </div>
                                        <button type="button" class="delete-image-btn" data-id="{{ $media->id }}"
                                            style="position:absolute;top:5px;right:5px;background:rgba(220,38,38,.9);color:#fff;border:none;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;z-index:2;">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div> --}}

                <div class="form-group">
                    <label class="form-label">Video URLs (YouTube/Vimeo)</label>
                    <div id="video-url-container" style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @if (is_array($project->video_urls))
                            @foreach ($project->video_urls as $url)
                                <div style="display: flex; gap: 0.5rem;">
                                    <input type="url" name="video_urls[]" class="form-control"
                                        value="{{ $url }}" placeholder="https://www.youtube.com/watch?v=...">
                                    <button type="button" class="btn-outline"
                                        style="border-color: #ef4444; color: #ef4444;"
                                        onclick="this.parentElement.remove()">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="url" name="video_urls[]" class="form-control"
                                placeholder="https://www.youtube.com/watch?v=...">
                            <button type="button" class="btn-primary"
                                style="background: rgba(255,255,255,0.1); border-color: transparent;" disabled><i
                                    class="fa-solid fa-link"></i></button>
                        </div>
                    </div>
                    @error('video_urls')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <button type="button" class="btn-outline" style="margin-top: 1rem; width: 100%;"
                        onclick="addVideoInput()">
                        <i class="fa-solid fa-plus"></i> Add Another Video
                    </button>
                </div>

                <!-- Submit -->
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
        document.querySelectorAll('.delete-image-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const mediaId = this.dataset.id;
                const mediaDiv = document.getElementById('media-' + mediaId);

                Swal.fire({
                    title: 'هل أنت متأكد من الحذف؟',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/projects/media/${mediaId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status) {
                                    mediaDiv.remove();
                                    Swal.fire('تم الحذف!', data.message, 'success');
                                } else {
                                    Swal.fire('خطأ', data.message, 'error');
                                }
                            })
                            .catch(() => {
                                Swal.fire('خطأ', 'حدث خطأ أثناء الحذف', 'error');
                            });
                    }
                });
            });
        });
    </script>

    <script>
        const maxPhotoSize = 5 * 1024 * 1024; // 5MB
        // const maxVideoSize = 10 * 1024 * 1024; // 10MB

        const photoInput = document.getElementById('photos');
        // const videoInput = document.getElementById('videos');
        const form = document.getElementById('projectForm');

        // Preview الصور الجديدة
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
                        img.style.marginRight = '10px';
                        container.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // Validate photos عند الاختيار
        if (photoInput) {
            photoInput.addEventListener('change', function() {
                const dt = new DataTransfer();
                let invalidFiles = [];

                Array.from(this.files).forEach(file => {
                    if (file.size > maxPhotoSize) invalidFiles.push(file.name);
                    else dt.items.add(file);
                });

                this.files = dt.files;

                if (invalidFiles.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'تم تجاهل بعض الملفات',
                        text: `تم استبعاد الصور التالية لأن حجمها أكبر من 5 ميجا:\n${invalidFiles.join(', ')}`
                    });
                }

                previewImages(this);
            });
        }

        // Validate videos عند الاختيار
        // if (videoInput) {
        //     videoInput.addEventListener('change', function() {
        //         const dt = new DataTransfer();
        //         let invalidFiles = [];

        //         Array.from(this.files).forEach(file => {
        //             if (file.size > maxVideoSize) invalidFiles.push(file.name);
        //             else dt.items.add(file);
        //         });

        //         this.files = dt.files;

        //         if (invalidFiles.length > 0) {
        //             Swal.fire({
        //                 icon: 'warning',
        //                 title: 'تم تجاهل بعض الملفات',
        //                 text: `تم استبعاد الفيديوهات التالية لأن حجمها أكبر من 10 ميجا:\n${invalidFiles.join(', ')}`
        //             });
        //         }
        //     });
        // }

        // Loading وقت submit
        if (form) {
            form.addEventListener('submit', function() {
                Swal.fire({
                    title: 'جاري تحديث المشروع...',
                    text: 'من فضلك انتظر',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            });
        }
    </script>
    <script>
        function addVideoInput() {
            const container = document.getElementById('video-url-container');
            const div = document.createElement('div');
            div.style.cssText = "display: flex; gap: 0.5rem;";
            div.innerHTML = `
                <input type="url" name="video_urls[]" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                <button type="button" class="btn-outline" style="border-color: #ef4444; color: #ef4444;" onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-trash"></i>
                </button>
            `;
            container.appendChild(div);
        }

        function deleteMedia(mediaId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('admin/projects/media') }}/${mediaId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status) {
                                document.getElementById(`media-${mediaId}`).remove();
                                Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
                            } else {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        });
                }
            })
        }
    </script>
@endsection
