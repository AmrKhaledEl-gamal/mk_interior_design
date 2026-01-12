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
            ->get()
            ->map(fn($user) => $this->formatUser($user));

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

        return response()->json([
            'status' => true,
            'data' => $this->formatUser($user, true)
        ]);
    }

    private function formatUser(User $user, bool $withProjects = false): array
    {
        $data = [
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

        if ($withProjects) {
            $projects = $user->projects()
                ->where('active', true)
                ->paginate(8);

            $projects->getCollection()->transform(
                fn($project) => $this->formatProject($project)
            );

            $data['projects'] = $projects;
        }

        return $data;
    }

    private function formatProject(Project $project): array
    {
        return [
            'id'   => $project->id,
            'slug' => $project->slug,
            'title' => $project->title,
        ];
    }
}
