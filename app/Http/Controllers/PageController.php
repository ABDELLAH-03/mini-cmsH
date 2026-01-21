<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display pages for a specific site
     */
    public function index(Site $site)
    {
        // Authorization check
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $pages = $site->pages()->with('children')->orderBy('order')->get();
        return view('pages.index', compact('site', 'pages'));
    }

    /**
     * Show form to create a new page
     */
    public function create(Site $site)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $parentPages = $site->pages()->whereNull('parent_id')->get();
        return view('pages.create', compact('site', 'parentPages'));
    }

    /**
     * Store a new page
     */
    public function store(Request $request, Site $site)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:pages,id',
        ]);

        // Generate slug
        $slug = Str::slug($request->title);
        $count = Page::where('site_id', $site->id)
            ->where('slug', 'like', "{$slug}%")
            ->count();

        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        // Create page
        $page = $site->pages()->create([
            'title' => $request->title,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'content' => ['sections' => []],
            'seo' => ['title' => $request->title, 'description' => ''],
            'order' => $site->pages()->count(),
        ]);

        return redirect()->route('sites.pages.edit', [$site, $page])
            ->with('success', 'Page created successfully!');
    }

    /**
     * Show page editor
     */
    public function edit(Site $site, Page $page)
    {
        if ($site->user_id !== Auth::id() || $page->site_id !== $site->id) {
            abort(403);
        }

        return view('pages.editor', compact('site', 'page'));
    }

    /**
     * Update page content
     */
    public function update(Request $request, Site $site, Page $page)
    {
        if ($site->user_id !== Auth::id() || $page->site_id !== $site->id) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|array',
            'seo.title' => 'required|string|max:255',
            'seo.description' => 'nullable|string|max:160',
        ]);

        $page->update([
            'content' => $request->content,
            'seo' => $request->seo,
        ]);

        return response()->json(['success' => true, 'message' => 'Page saved!']);
    }

    /**
     * Update page order (for drag & drop)
     */
    public function updateOrder(Request $request, Site $site)
    {
        if ($site->user_id !== Auth::id()) {
            abort(403);
        }

        foreach ($request->order as $order => $pageId) {
            Page::where('id', $pageId)
                ->where('site_id', $site->id)
                ->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Delete a page
     */
    public function destroy(Site $site, Page $page)
    {
        if ($site->user_id !== Auth::id() || $page->site_id !== $site->id) {
            abort(403);
        }

        // Don't allow deleting homepage
        if ($page->is_homepage) {
            return back()->withErrors(['error' => 'Cannot delete homepage. Set another page as homepage first.']);
        }

        $page->delete();
        return back()->with('success', 'Page deleted successfully.');
    }

    /**
     * Set a page as homepage
     */
    public function setHomepage(Site $site, Page $page)
    {
        if ($site->user_id !== Auth::id() || $page->site_id !== $site->id) {
            abort(403);
        }

        // Remove homepage from other pages
        $site->pages()->update(['is_homepage' => false]);

        // Set this page as homepage
        $page->update(['is_homepage' => true]);

        return back()->with('success', 'Homepage updated successfully.');
    }
    /**
     * Publish a page
     */
    public function publish(Site $site, Page $page)
    {
        if ($site->user_id !== Auth::id() || $page->site_id !== $site->id) {
            abort(403);
        }

        $page->update([
            'published_at' => now()
        ]);

        return back()->with('success', 'Page published successfully!');
    }

    /**
     * Unpublish a page
     */
    public function unpublish(Site $site, Page $page)
    {
        if ($site->user_id !== Auth::id() || $page->site_id !== $site->id) {
            abort(403);
        }

        $page->update([
            'published_at' => null
        ]);

        return back()->with('success', 'Page unpublished.');
    }
    /**
     * Delete a specific section from a page
     */
    public function deleteSection(Request $request, Site $site, Page $page)
    {
        if ($site->user_id !== Auth::id() || $page->site_id !== $site->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'section_id' => 'required|string',
        ]);

        // Get current content
        $content = $page->content ?? ['sections' => []];

        // Filter out the section to delete
        $newSections = array_filter($content['sections'], function ($section) use ($request) {
            return ($section['id'] ?? '') !== $request->section_id;
        });

        // Re-index array
        $content['sections'] = array_values($newSections);

        // Update page
        $page->update(['content' => $content]);

        return response()->json([
            'success' => true,
            'message' => 'Section deleted',
            'remaining_sections' => count($content['sections'])
        ]);
    }
}