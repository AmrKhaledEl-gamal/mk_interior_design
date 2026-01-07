<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectAnalyticsController extends Controller
{
    public function toggleLike(Project $project): JsonResponse
    {
        // For simple like/unlike via API, we can just increment/decrement
        // Does the user want strict toggle logic or just separate endpoints?
        // "increase or decrease from api only"
        // Let's implement specific actions for flexibility + a toggle helper if needed.

        // Actually, assuming standard behavior:
        // If checking previous state is hard (stateless API), maybe just endpoints?
        // Let's stick to toggle for "like" button usage, or separate up/down if requested.
        // User said "views and like in increase or decrease".
        // Let's provide explicit methods.
        return response()->json(['message' => 'Use specific endpoints']);
    }

    public function like(Project $project): JsonResponse
    {
        $project->increment('likes');
        return response()->json([
            'status' => true,
            'likes' => $project->likes,
            'message' => 'Like added'
        ]);
    }

    public function unlike(Project $project): JsonResponse
    {
        if ($project->likes > 0) {
            $project->decrement('likes');
        }
        return response()->json([
            'status' => true,
            'likes' => $project->likes,
            'message' => 'Like removed'
        ]);
    }
}
