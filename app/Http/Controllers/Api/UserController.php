<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with(['media'])
            ->where('active', true)
            ->where('is_approved', true)
            ->get();

        $data = $users->transform(function ($user) {
            return $this->formatUser($user);
        });

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }


    public function show(User $user): JsonResponse
    {
        if (!$user->active || !$user->is_approved) {
            return response()->json(['status' => false, 'message' => 'User not found or inactive'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $this->formatUser($user)
        ]);
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'photo' => $user->getFirstMediaUrl('avatars') ?: null, // Assumes 'avatar' collection
            'cover' => $user->getFirstMediaUrl('covers') ?: null, // Assumes 'avatar' collection
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone1' => $user->phone1,
            'phone2' => $user->phone2,
            'description' => $user->description,
            'projects' => $user->projects()->where('active', true)->get()->paginate(8)
            // ->transform(function ($project) {
            //     return $this->formatProject($project)
            // ;
            // })
            ,
            'created_at' => $user->created_at,
        ];
    }

    // private function formatProject(Project $project): array
    // {
    //     return [
    //         'id' => $project->id,
    //         'slug' => $project->slug,
    //     ] + $this->formatProject($project);
    // }
}
