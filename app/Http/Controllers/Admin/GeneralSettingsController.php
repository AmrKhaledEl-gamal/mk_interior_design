<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsController extends Controller
{
    public function index()
    {
        $settings = app(GeneralSettings::class);
        return view('admin.settings.general.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'support_email' => 'nullable|email|max:255',
            'info_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'footer_copyright' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        $settings = app(GeneralSettings::class);

        // Handle file uploads
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            if ($settings->site_logo && Storage::disk('public')->exists($settings->site_logo)) {
                Storage::disk('public')->delete($settings->site_logo);
            }

            $logoPath = $request->file('site_logo')->store('settings/logos', 'public');
            $settings->site_logo = $logoPath;
        }

        if ($request->hasFile('site_favicon')) {
            // Delete old favicon if exists
            if ($settings->site_favicon && Storage::disk('public')->exists($settings->site_favicon)) {
                Storage::disk('public')->delete($settings->site_favicon);
            }

            $faviconPath = $request->file('site_favicon')->store('settings/favicons', 'public');
            $settings->site_favicon = $faviconPath;
        }

        // Update other settings
        $settings->site_name = $request->site_name;
        $settings->support_email = $request->support_email;
        $settings->info_email = $request->info_email;
        $settings->address = $request->address;
        $settings->phone = $request->phone;
        $settings->footer_copyright = $request->footer_copyright;
        $settings->facebook_url = $request->facebook_url;
        $settings->whatsapp_url = $request->whatsapp_url;
        $settings->instagram_url = $request->instagram_url;

        $settings->save();

        return redirect()->back()->with('success', 'General settings updated successfully!');
    }

    public function removeLogo()
    {
        $settings = app(GeneralSettings::class);

        if ($settings->site_logo && Storage::disk('public')->exists($settings->site_logo)) {
            Storage::disk('public')->delete($settings->site_logo);
        }

        $settings->site_logo = null;
        $settings->save();

        return redirect()->back()->with('success', 'Logo removed successfully!');
    }

    public function removeFavicon()
    {
        $settings = app(GeneralSettings::class);

        if ($settings->site_favicon && Storage::disk('public')->exists($settings->site_favicon)) {
            Storage::disk('public')->delete($settings->site_favicon);
        }

        $settings->site_favicon = null;
        $settings->save();

        return redirect()->back()->with('success', 'Favicon removed successfully!');
    }
}
