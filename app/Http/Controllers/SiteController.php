<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Auth::user()->sites()->latest()->get();
        return view('sites.index', compact('sites'));
    }

    public function create()
    {
        return view('sites.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|alpha_dash|unique:sites,subdomain'
        ]);

        $site = Auth::user()->sites()->create([
            'name' => $request->name,
            'subdomain' => $request->subdomain,
            'settings' => [
                'primary_color' => '#3B82F6',
                'font_family' => 'Inter',
                'container_width' => '1200px'
            ]
        ]);

        return redirect()->route('sites.edit', $site)
            ->with('success', 'Site created successfully!');
    }

    public function show(Site $site)
    {
        return view('sites.show', compact('site'));
    }

    public function edit(Site $site)
    {
        // Add authorization check
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        return view('sites.edit', compact('site'));
    }

    public function update(Request $request, Site $site)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,published',
        ]);

        $site->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('sites.edit', $site)
            ->with('success', 'Site updated successfully!');
    }
    public function destroy(Site $site)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $site->delete();
        return redirect()->route('sites.index')
            ->with('success', 'Site deleted!');
    }
}