<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function inactive()
    {
        $projects = Project::where('active', false)->latest()->paginate(12);
        return view('admin.projects.inactive', compact('projects'));
    }

    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    public function toggleStatus(Project $project)
    {
        if (!$project->active) {
            // Attempting to activate
            if ($project->user && (!$project->user->active || !$project->user->is_approved)) {
                return back()->with('error', 'Cannot activate project. Project owner is not active or approved.');
            }
        }

        $project->active = !$project->active;
        $project->save();

        return back()->with('success', 'Project status updated successfully.');
    }
}
