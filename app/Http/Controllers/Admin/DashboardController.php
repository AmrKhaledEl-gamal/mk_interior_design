<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'active_projects' => Project::where('active', true)->count(),
            'total_clients' => User::count(),
            'recent_inquiries' => [], // Placeholder
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
