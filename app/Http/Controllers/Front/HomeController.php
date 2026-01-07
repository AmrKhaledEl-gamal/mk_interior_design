<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DataDelivery;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_projects' => $user->projects()->count(),
            'active_projects' => $user->projects()->where('active', true)->count(),
            'inactive_projects' => $user->projects()->where('active', false)->count(),
            'total_views' => $user->projects()->sum('views'),
        ];

        $recentProjects = $user->projects()->latest()->take(4)->get();

        return view('front.index', compact('stats', 'recentProjects'));
    }
}
