<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Support\Str;

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
        $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'nullable|string|max:255',
        ]);
        $project = Project::create([
            'name' => [
                'en' => $request->name['en'],
                'ar' => $request->name['ar'],
            ],
            'slug' => Str::slug($request->name['en']),
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
            'slug' => Str::slug($request->name['en']),
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
            $manager = new \Intervention\Image\ImageManager(
                new \Intervention\Image\Drivers\Gd\Driver()
            );

            foreach ($request->file('photos') as $file) {
                $webpName = Str::uuid() . '.webp';
                $tempPath = storage_path('app/temp/' . $webpName);

                if (!file_exists(dirname($tempPath))) {
                    mkdir(dirname($tempPath), 0755, true);
                }

                $image = $manager->read($file);

                // ✅ نفس الضغط + التحويل + تحسين
                $image
                    ->orient() // تصحيح اتجاه الصورة
                    // ->resize(
                    //     1200,
                    //     1600,
                    //     function ($constraint) {
                    //         $constraint->aspectRatio();
                    //         $constraint->upsize();
                    //     }
                    // )
                    ->toWebp(85)
                    ->save($tempPath);

                $project
                    ->addMedia($tempPath)
                    ->usingFileName($webpName)
                    ->toMediaCollection('photos');

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
