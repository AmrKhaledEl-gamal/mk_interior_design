@extends('front.layouts.app')
@section('content')
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <!-- Back Button -->
            <a href="{{ route('front.projects.index') }}" class="btn-outline" style="text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </header>

        <div class="settings-section">
            <form action="{{ route('front.projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Project Title (English)</label>
                        <input type="text" name="name[en]" class="form-control" placeholder="e.g. Skyline Tower"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="text-align: right; display: block;">Project Title
                            (Arabic)</label>
                        <input type="text" name="name[ar]" class="form-control" placeholder="مثال: برج الأفق"
                            dir="rtl">
                    </div>
                </div>



                <div class="form-group">
                    <label class="form-label">Project Photos (Upload Multiple)</label>
                    <div
                        style="border: 2px dashed var(--border); padding: 2rem; text-align: center; border-radius: 12px; background: rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-images"
                            style="font-size: 2rem; color: var(--text-secondary); margin-bottom: 1rem;"></i>
                        <p style="color: var(--text-secondary); margin-bottom: 1rem;">Drag and drop your images here
                        </p>
                        <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Project Videos (Upload Multiple)</label>
                    <div
                        style="border: 2px dashed var(--border); padding: 2rem; text-align: center; border-radius: 12px; background: rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-film"
                            style="font-size: 2rem; color: var(--text-secondary); margin-bottom: 1rem;"></i>
                        <p style="color: var(--text-secondary); margin-bottom: 1rem;">Drag and drop your videos here
                        </p>
                        <input type="file" name="videos[]" class="form-control" multiple accept="video/*">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn-primary">Create Project</button>
                    <a href="{{ route('front.projects.index') }}" class="btn-outline"
                        style="text-decoration: none; text-align:center;">Cancel</a>
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
                icon: 'error',
                title: 'خطأ في رفع الملفات',
                text: '{{ $errors->first() }}',
                confirmButtonText: 'حاول تاني'
            });
        </script>
    @endif
@endsection
