@extends('admin.layout.layout')

@section('content')
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 style="font-size: 1.5rem; font-weight: 600;">General Settings</h2>
            </div>
        </header>

        <div class="settings-section">
            <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Site Information -->
                <div class="form-group">
                    <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-primary);">Site
                        Information</h3>
                </div>

                <div class="form-group">
                    <label class="form-label">Site Name <span style="color:red">*</span></label>
                    <input type="text" class="form-control" name="site_name"
                        value="{{ old('site_name', $settings->site_name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Site Logo</label>
                    <div style="display: flex; gap: 1rem; align-items: start;">
                        @if ($settings->site_logo)
                            <div
                                style="position: relative; width: 100px; height: 100px; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; background: #fff;">
                                <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo"
                                    style="width: 100%; height: 100%; object-fit: contain;">
                                <button type="button" onclick="removeLogo()"
                                    style="position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;">&times;</button>
                            </div>
                        @endif
                        <div style="flex: 1;">
                            <input type="file" name="site_logo" class="form-control" accept="image/*"
                                style="height: auto; padding: 10px;">
                            <small style="color: var(--text-secondary); display: block; margin-top: 5px;">Upload a new logo
                                to replace the current one.</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Site Favicon</label>
                    <div style="display: flex; gap: 1rem; align-items: start;">
                        @if ($settings->site_favicon)
                            <div
                                style="position: relative; width: 64px; height: 64px; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; background: #fff;">
                                <img src="{{ asset('storage/' . $settings->site_favicon) }}" alt="Favicon"
                                    style="width: 100%; height: 100%; object-fit: contain;">
                                <button type="button" onclick="removeFavicon()"
                                    style="position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;">&times;</button>
                            </div>
                        @endif
                        <div style="flex: 1;">
                            <input type="file" name="site_favicon" class="form-control" accept=".ico,.png"
                                style="height: auto; padding: 10px;">
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="form-group" style="margin-top: 2rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-primary);">
                        Contact Information</h3>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Support Email</label>
                        <input type="email" class="form-control" name="support_email"
                            value="{{ old('support_email', $settings->support_email) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Info Email</label>
                        <input type="email" class="form-control" name="info_email"
                            value="{{ old('info_email', $settings->info_email) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $settings->phone) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" rows="3">{{ old('address', $settings->address) }}</textarea>
                </div>

                <!-- Footer & Social -->
                <div class="form-group" style="margin-top: 2rem;">
                    <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-primary);">Footer
                        & Social</h3>
                </div>

                <div class="form-group">
                    <label class="form-label">Footer Copyright</label>
                    <input type="text" class="form-control" name="footer_copyright"
                        value="{{ old('footer_copyright', $settings->footer_copyright) }}">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Facebook URL</label>
                        <input type="url" class="form-control" name="facebook_url"
                            value="{{ old('facebook_url', $settings->facebook_url) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">WhatsApp URL</label>
                        <input type="url" class="form-control" name="whatsapp_url"
                            value="{{ old('whatsapp_url', $settings->whatsapp_url) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Instagram URL</label>
                        <input type="url" class="form-control" name="instagram_url"
                            value="{{ old('instagram_url', $settings->instagram_url) }}">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn-primary">Save Settings</button>
                    <button type="button" class="btn-outline" onclick="location.reload()">Reset</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Hidden Forms for Removal -->
    <form id="remove-logo-form" action="{{ route('admin.settings.general.remove-logo') }}" method="POST"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <form id="remove-favicon-form" action="{{ route('admin.settings.general.remove-favicon') }}" method="POST"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
    <script>
        function removeLogo() {
            if (confirm('Remove logo?')) {
                document.getElementById('remove-logo-form').submit();
            }
        }

        function removeFavicon() {
            if (confirm('Remove favicon?')) {
                document.getElementById('remove-favicon-form').submit();
            }
        }
    </script>
@endsection
