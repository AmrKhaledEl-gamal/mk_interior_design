<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('front.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone1' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048', // 2MB Max
            'cover' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'description' => $request->description,
        ]);

        if ($request->hasFile('photo')) {
            $manager = new \Intervention\Image\ImageManager(
                new \Intervention\Image\Drivers\Gd\Driver()
            );

            $file = $request->file('photo');

            $webpName = Str::uuid() . '.webp';
            $tempPath = storage_path('app/temp/' . $webpName);

            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            $manager->read($file)
                ->orient()
                ->toWebp(85)
                ->save($tempPath);

            // ❗ لو عايز تستبدل الصورة القديمة
            $user->clearMediaCollection('avatars');

            $user
                ->addMedia($tempPath)
                ->usingFileName($webpName)
                ->toMediaCollection('avatars');

            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }


        if ($request->hasFile('cover')) {
            $manager = new \Intervention\Image\ImageManager(
                new \Intervention\Image\Drivers\Gd\Driver()
            );

            $file = $request->file('cover');

            $webpName = Str::uuid() . '.webp';
            $tempPath = storage_path('app/temp/' . $webpName);

            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            $manager->read($file)
                ->orient()
                ->toWebp(85)
                ->save($tempPath);

            $user->clearMediaCollection('covers');

            $user
                ->addMedia($tempPath)
                ->usingFileName($webpName)
                ->toMediaCollection('covers');

            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }


        return redirect()->route('front.profile')->with('success', 'Profile updated successfully!');
    }
}
