<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects()->latest()->paginate(12);
        return view('front.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('front.projects.create');
    }

    public function store(ProjectRequest $request)
    {
        $project = Project::create([
            'name' => [
                'en' => $request->name['en'],
                'ar' => $request->name['ar'],
            ],
            'user_id' => auth()->id(),
        ]);

        $this->handleMedia($project, $request);

        return redirect()
            ->route('front.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return view('front.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('front.projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update([
            'name' => [
                'en' => $request->name['en'],
                'ar' => $request->name['ar'],
            ],
        ]);

        $this->handleMedia($project, $request);

        return redirect()
            ->route('front.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()
            ->route('front.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    private function handleMedia(Project $project, $request): void
    {
        if ($request->hasFile('photos')) {
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());

            foreach ($request->file('photos') as $file) {
                $webpName = \Illuminate\Support\Str::uuid() . '.webp';
                $tempPath = storage_path('app/temp/' . $webpName);

                // Ensure temp directory exists
                if (!file_exists(dirname($tempPath))) {
                    mkdir(dirname($tempPath), 0755, true);
                }

                // Process image: orientate, convert to webp, save
                $manager->read($file)
                    ->toWebp(85)
                    ->save($tempPath);

                // Upload to Media Library
                $project
                    ->addMedia($tempPath)
                    ->usingFileName($webpName)
                    ->toMediaCollection('photos');

                // Cleanup
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $project->addMedia($video)->toMediaCollection('videos');
            }
        }
    }

    public function deleteMedia($id)
    {
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($id);
        if ($media->model_type !== Project::class) {
            return response()->json(['status' => false, 'message' => 'Unauthorized source'], 403);
        }
        $media->delete();
        return response()->json(['status' => true, 'message' => 'Media deleted successfully']);
    }
}
