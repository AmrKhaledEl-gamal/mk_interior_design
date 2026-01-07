<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->is_approved) {
            return redirect()->route('front.home');
        }
        return view('front.approval_notice');
    }

    public function store(Request $request)
    {
        $request->validate([
            'portfolio_link' => 'nullable|url',
            'portfolio_pdf' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $user = Auth::user();

        if ($request->hasFile('portfolio_pdf')) {
            $path = $request->file('portfolio_pdf')->store('portfolios', 'public');
            $user->portfolio_pdf = $path;
        }

        if ($request->filled('portfolio_link')) {
            $user->portfolio_link = $request->input('portfolio_link');
        }

        $user->save();

        return redirect()->route('front.approval.notice')->with('success', 'Your info has been submitted successfully.');
    }
}
