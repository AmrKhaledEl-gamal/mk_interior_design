<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::with('media')
            ->where('active', true)
            ->latest()
            ->paginate(12);

        $items = $projects->getCollection()
            ->map(fn(Project $project) => $this->formatProject($project))
            ->values();

        return response()->json([
            'status' => true,
            'data' => [
                'items'    => $items,
                'has_more' => $projects->hasMorePages(),
            ],
        ]);
    }

    public function show(Project $project): JsonResponse
    {
        if (!$project->active) {
            return response()->json([
                'status' => false,
                'message' => 'Project not found'
            ], 404);
        }

        // optional
        $project->increment('views');

        return response()->json([
            'status' => true,
            'data' => $this->formatProject($project),
        ]);
    }

    private function formatProject(Project $project): array
    {
        $locale = app()->getLocale();
        $name = $project->name;

        if (is_array($name)) {
            $name = $name[$locale] ?? $name['en'] ?? '';
        }

        return [
            'id'         => $project->id,
            'user_id'    => $project->user_id,
            'slug'       => $project->slug,
            'name'       => (string) $name,
            'views'      => $project->views,
            'likes'      => $project->likes,
            'created_at' => $project->created_at,

            'media' => [
                'photos' => $project->getMedia('photos')
                    ->map(fn($media) => $media->getUrl())
                    ->values(),

                'videos' => $project->getMedia('videos')
                    ->map(fn($media) => $media->getUrl())
                    ->values(),
            ],
        ];
    }
}
