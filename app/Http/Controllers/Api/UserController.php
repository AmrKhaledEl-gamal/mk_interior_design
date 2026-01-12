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
        $users = User::with('media')
            ->where('active', true)
            ->where('is_approved', true)
            ->get()
            ->map(fn(User $user) => $this->formatUser($user));

        return response()->json([
            'status' => true,
            'data' => $users,
        ]);
    }

    public function show(User $user): JsonResponse
    {
        if (!$user->active || !$user->is_approved) {
            return response()->json([
                'status' => false,
                'message' => 'User not found or inactive'
            ], 404);
        }

        $projects = $user->projects()
            ->where('active', true)
            ->select('id', 'slug')
            ->paginate(8);

        $items = $projects->getCollection()
            ->map(fn(Project $project) => $this->formatProject($project))
            ->values();

        return response()->json([
            'status' => true,
            'data' => [
                'user' => $this->formatUser($user),
                'projects' => [
                    'items'    => $items,
                    'has_more' => $projects->hasMorePages(),
                ],
            ],
        ]);
    }

    private function formatUser(User $user): array
    {
        return [
            'id'          => $user->id,
            'photo'       => $user->getFirstMediaUrl('avatars'),
            'cover'       => $user->getFirstMediaUrl('covers'),
            'first_name'  => $user->first_name,
            'last_name'   => $user->last_name,
            'email'       => $user->email,
            'phone1'      => $user->phone1,
            'phone2'      => $user->phone2,
            'description' => $user->description,
            'created_at'  => $user->created_at,
        ];
    }

    private function formatProject(Project $project): array
    {
        return [
            'id'    => $project->id,
            'slug'  => $project->slug,
        ];
    }
}
