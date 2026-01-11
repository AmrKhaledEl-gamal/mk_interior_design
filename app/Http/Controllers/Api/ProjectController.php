<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::with(['media'])
            ->where('active', true)->latest()->paginate(12);

        $data = $projects->getCollection()->transform(function ($project) {
            return $this->formatProject($project);
        });

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function show(Project $project): JsonResponse
    {
        if (!$project->active) {
            return response()->json(['status' => false, 'message' => 'Project not found'], 404);
        }

        // Ensure views are incremented via the separate analytics endpoint if strict separation is desired,
        // OR we can leave it to the client to call the increment endpoint.
        // "make a project api dont foret get images , videos from media" -> Focus on data retrieval.
        $project->increment('views');

        return response()->json([
            'status' => true,
            'data' => $this->formatProject($project)
        ]);
    }

    private function formatProject(Project $project): array
    {
        $locale = app()->getLocale();
        $name = $project->name;

        // Handle localization manually if name is array
        if (is_array($name)) {
            $name = $name[$locale] ?? $name['en'] ?? $name;
        }

        return [
            'id' => $project->id,
            'user_id' => $project->user_id,
            'slug' => $project->slug,
            'name' => (string) $name,
            'views' => $project->views,
            'likes' => $project->likes,
            'created_at' => $project->created_at,
            'media' => [
                'photos' => $project->getMedia('photos')->map(function ($media) {
                    return [
                        'url' => $media->getUrl(),
                    ];
                }),
                'videos' => $project->getMedia('videos')->map(function ($media) {
                    return [
                        'url' => $media->getUrl(),
                    ];
                }),
            ]
        ];
    }
}
