@extends('front.layouts.app')

@section('content')
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
            <a href="{{ route('front.projects.index') }}" class="btn-outline" style="text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </header>

        <div class="settings-section">
            <form id="projectForm" action="{{ route('front.projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Project Titles -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Project Title (English)</label>
                        <input type="text" name="name[en]" class="form-control" placeholder="e.g. Skyline Tower"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="text-align: right; display: block;">Project Title (Arabic)</label>
                        <input type="text" name="name[ar]" class="form-control" placeholder="مثال: برج الأفق"
                            dir="rtl">
                    </div>
                </div>

                <!-- Project Photos -->
                <div class="form-group">
                    <label class="form-label">Project Photos (Upload Multiple)</label>
                    <div
                        style="border: 2px dashed var(--border); padding: 2rem; text-align: center; border-radius: 12px; background: rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-images"
                            style="font-size: 2rem; color: var(--text-secondary); margin-bottom: 1rem;"></i>
                        <p style="color: var(--text-secondary); margin-bottom: 1rem;">Drag and drop your images here</p>
                        <input type="file" id="photos" name="photos[]" class="form-control" multiple accept="image/*">
                    </div>
                </div>

                <!-- Project Videos -->
                <div class="form-group">
                    <label class="form-label">Project Videos (Upload Multiple)</label>
                    <div
                        style="border: 2px dashed var(--border); padding: 2rem; text-align: center; border-radius: 12px; background: rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-film"
                            style="font-size: 2rem; color: var(--text-secondary); margin-bottom: 1rem;"></i>
                        <p style="color: var(--text-secondary); margin-bottom: 1rem;">Drag and drop your videos here</p>
                        <input type="file" id="videos" name="videos[]" class="form-control" multiple accept="video/*">
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn-primary">Create Project</button>
                    <a href="{{ route('front.projects.index') }}" class="btn-outline"
                        style="text-decoration: none; text-align:center;">
                        Cancel
                    </a>
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
        const maxPhotoSize = 5 * 1024 * 1024; // 5MB
        const maxVideoSize = 10 * 1024 * 1024; // 10MB

        const photoInput = document.getElementById('photos');
        const videoInput = document.getElementById('videos');
        const form = document.getElementById('projectForm');

        // Validate photos عند الاختيار
        photoInput.addEventListener('change', function() {
            const dt = new DataTransfer();
            let invalidFiles = [];

            for (let file of this.files) {
                if (file.size > maxPhotoSize) {
                    invalidFiles.push(file.name);
                } else {
                    dt.items.add(file);
                }
            }

            this.files = dt.files; // تحديث الملفات الصالحة فقط

            if (invalidFiles.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تم تجاهل بعض الملفات',
                    text: `تم استبعاد الصور التالية لأن حجمها أكبر من 5 ميجا:\n${invalidFiles.join(', ')}`,
                });
            }
        });

        // Validate videos عند الاختيار
        videoInput.addEventListener('change', function() {
            const dt = new DataTransfer();
            let invalidFiles = [];

            for (let file of this.files) {
                if (file.size > maxVideoSize) {
                    invalidFiles.push(file.name);
                } else {
                    dt.items.add(file);
                }
            }

            this.files = dt.files; // تحديث الملفات الصالحة فقط

            if (invalidFiles.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تم تجاهل بعض الملفات',
                    text: `تم استبعاد الفيديوهات التالية لأن حجمها أكبر من 10 ميجا:\n${invalidFiles.join(', ')}`,
                });
            }
        });

        // Loading وقت submit
        form.addEventListener('submit', function() {
            Swal.fire({
                title: 'جاري رفع المشروع...',
                text: 'من فضلك انتظر',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        });
    </script>
@endsection
