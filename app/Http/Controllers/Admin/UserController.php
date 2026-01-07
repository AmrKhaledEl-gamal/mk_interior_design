<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //search
        $search = request('search');
        $users = User::where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone1', 'like', "%{$search}%")
            ->orWhere('phone2', 'like', "%{$search}%")
            ->paginate(10);

        return view('admin.users.usersList', compact('users', 'search'));
    }

    public function pending()
    {
        $users = User::where('is_approved', false)->paginate(10);
        return view('admin.users.pending', compact('users'));
    }

    public function approve(User $user)
    {
        $user->update([
            'is_approved' => true,
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'User approved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.addUser');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone2' => ['nullable', 'string', 'max:20'],
            'phone1' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone1' => $request->phone1,
            'phone2' => $request->phone2,
            'description' => $request->description,
            'active' => $request->has('active'),
            'password' => Hash::make($request->password),
        ]);

        if ($request->hasFile('photo')) {
            $user->addMediaFromRequest('photo')->toMediaCollection('avatars');
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // If there is a view profile page, use it. Otherwise reusing edit or list.
        // Based on file list, there is viewProfile.blade.php
        $projects = $user->projects()->latest()->paginate(12);
        return view('admin.users.viewProfile', compact('user', 'projects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.editUser', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone1' => ['nullable', 'string', 'max:20'],
            'phone2' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone1 = $request->phone1;
        $user->phone2 = $request->phone2;
        $user->description = $request->description;
        $user->is_approved = $request->has('is_approved');
        // User cannot be active if not approved
        $user->active = $request->has('active') && $user->is_approved;

        // If user becomes inactive or unapproved, deactivate their projects
        if (!$user->active || !$user->is_approved) {
            $user->projects()->update(['active' => false]);
        }


        if ($request->hasFile('photo')) {
            $user->clearMediaCollection('avatars');
            $user->addMediaFromRequest('photo')->toMediaCollection('avatars');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
