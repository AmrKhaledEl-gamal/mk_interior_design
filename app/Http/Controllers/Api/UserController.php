<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    // GET /api/users
    public function index(): JsonResponse
    {
        $users = User::withCount(['projects' => function ($q) {
            $q->where('active', true);
        }])
            ->where('active', true)
            ->where('is_approved', true)
            ->paginate(12); // users per page

        $items = $users->getCollection()
            ->map(fn($user) => $this->formatUserIndex($user))
            ->values();

        return response()->json([
            'status' => true,
            'data' => [
                'items' => $items,
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    // GET /api/users/{user}
    public function show(User $user): JsonResponse
    {
        if (!$user->active || !$user->is_approved) {
            return response()->json([
                'status' => false,
                'message' => 'User not found or inactive'
            ], 404);
        }

        // Pagination للـ projects (8 per page)
        $projects = $user->projects()
            ->where('active', true)
            ->select('slug')
            ->paginate(8);

        $items = $projects->getCollection()->pluck('slug'); // Array of slugs

        return response()->json([
            'status' => true,
            'data' => [
                'user' => $this->formatUserShow($user),
                'projects' => [
                    'items' => $items,
                    'total' => $projects->total(),
                    'current_page' => $projects->currentPage(),
                    'per_page' => $projects->perPage(),
                    'last_page' => $projects->lastPage(),
                ]
            ],
        ]);
    }

    private function formatUserIndex(User $user): array
    {
        return [
            'id' => $user->id,
            'photo' => $user->getFirstMediaUrl('avatars'),
            'cover' => $user->getFirstMediaUrl('covers'),
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone1' => $user->phone1,
            'phone2' => $user->phone2,
            'description' => $user->description,
            'created_at' => $user->created_at,
            'projects_count' => $user->projects_count,
        ];
    }

    private function formatUserShow(User $user): array
    {
        return [
            'id' => $user->id,
            'photo' => $user->getFirstMediaUrl('avatars'),
            'cover' => $user->getFirstMediaUrl('covers'),
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone1' => $user->phone1,
            'phone2' => $user->phone2,
            'description' => $user->description,
            'created_at' => $user->created_at,
        ];
    }
}
