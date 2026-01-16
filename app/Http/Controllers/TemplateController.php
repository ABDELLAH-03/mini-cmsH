<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Site;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    /**
     * Display templates for the current user
     */
    /**
     * Display templates for the current user
     */
    public function index(Request $request)
    {
        // Handle AJAX request from page editor sidebar
        if ($request->ajax() || $request->has('ajax')) {
            $user = Auth::user();

            $templates = Template::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('visibility', 'public')
                    ->orWhere('visibility', 'system');
            })
                ->latest()
                ->limit(6)  // Only show 6 in sidebar
                ->get();

            return view('templates.partials.sidebar-list', compact('templates'));
        }

        $user = Auth::user();

        $categories = ['general', 'business', 'portfolio', 'blog', 'ecommerce'];
        $selectedCategory = $request->category ?? 'all';

        // Query templates
        $query = Template::query();

        if ($selectedCategory !== 'all') {
            $query->where('category', $selectedCategory);
        }

        // Show user's private templates, public templates, and system templates
        $templates = $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhere('visibility', 'public')
                ->orWhere('visibility', 'system');
        })->latest()->paginate(12);

        return view('templates.index', compact('templates', 'categories', 'selectedCategory'));
    }
    /**
     * Show form to create a new template
     */
    public function create(Request $request)
    {
        $siteId = $request->site_id;
        $pageId = $request->page_id;

        $sites = Auth::user()->sites()->get();
        $pages = collect();

        if ($siteId) {
            $pages = Page::where('site_id', $siteId)->get();
        }

        return view('templates.create', compact('sites', 'pages', 'siteId', 'pageId'));
    }

    /**
     * Store a new template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hero,content,features,contact,testimonial,full_page,layout',
            'category' => 'required|string|max:100',
            'visibility' => 'required|in:private,public',
            'content' => 'required|array',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        $template = Template::create([
            'user_id' => Auth::id(),
            'site_id' => $request->site_id,
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'visibility' => $request->visibility,
            'content' => $request->content,
            'preview_data' => [
                'html' => $this->generatePreviewHtml($request->type, $request->content),
                'colors' => ['primary' => '#3B82F6', 'secondary' => '#10B981']
            ],
        ]);

        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully!');
    }

    /**
     * Apply a template to a page
     */
    /**
     * Apply a template to a page
     */
    public function apply(Request $request)
    {
        // Validate request
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'site_id' => 'required|exists:sites,id',
            'page_id' => 'required|exists:pages,id',
            'full_page' => 'boolean'
        ]);

        $user = Auth::user();
        $site = Site::findOrFail($request->site_id);
        $page = Page::findOrFail($request->page_id);
        $template = Template::findOrFail($request->template_id);

        // Authorization checks
        if ($site->user_id !== $user->id) {
            return response()->json(['error' => 'You do not own this site'], 403);
        }

        if ($page->site_id !== $site->id) {
            return response()->json(['error' => 'Page does not belong to this site'], 403);
        }

        // Check template permission
        if ($template->visibility === 'private' && $template->user_id !== $user->id) {
            return response()->json(['error' => 'This is a private template'], 403);
        }

        // Get current page content
        $currentContent = $page->content ?? ['sections' => []];

        // Add template sections to page
        if ($request->boolean('full_page') || $template->type === 'full_page') {
            // Replace entire page with template
            $page->content = $template->content;
            $message = 'Full page template applied successfully!';
        } else {
            // Add template as a new section
            $newSection = [
                'id' => 'template_' . $template->id . '_' . time(),
                'type' => $template->type,
                'content' => $template->content,
                'template_id' => $template->id,
                'settings' => []
            ];

            $currentContent['sections'][] = $newSection;
            $page->content = $currentContent;
            $message = 'Template section added successfully!';
        }

        $page->save();

        // Increment template usage
        $template->increment('usage_count');

        return response()->json([
            'success' => true,
            'message' => $message,
            'template_type' => $template->type,
            'template_content' => $template->content
        ]);
    }

    /**
     * Save current page as a template
     */
    public function saveFromPage(Request $request, Site $site, Page $page)
    {
        if ($site->user_id !== Auth::id() || $page->site_id !== $site->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hero,content,features,contact,testimonial,full_page,layout',
            'category' => 'required|string|max:100',
            'visibility' => 'required|in:private,public',
        ]);

        $template = Template::create([
            'user_id' => Auth::id(),
            'site_id' => $site->id,
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'visibility' => $request->visibility,
            'content' => $page->content,
            'preview_data' => [
                'html' => $this->generatePreviewHtml($request->type, $page->content),
                'colors' => ['primary' => '#3B82F6', 'secondary' => '#10B981']
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Template saved successfully!',
            'template' => $template
        ]);
    }

    /**
     * Delete a template
     */
    public function destroy(Template $template)
    {
        // Only allow deleting own templates (except system templates)
        if ($template->user_id !== Auth::id() || $template->visibility === 'system') {
            abort(403);
        }

        $template->delete();

        return back()->with('success', 'Template deleted successfully.');
    }

    /**
     * Generate preview HTML for template
     */
    private function generatePreviewHtml($type, $content)
    {
        switch ($type) {
            case 'hero':
                $title = $content['title'] ?? 'Hero Title';
                return "<div class='hero-preview'><h3>{$title}</h3></div>";
            case 'content':
                return "<div class='content-preview'>Content Section</div>";
            case 'features':
                $count = count($content['items'] ?? []);
                return "<div class='features-preview'>{$count} features</div>";
            case 'full_page':
                $sections = count($content['sections'] ?? []);
                return "<div class='fullpage-preview'>{$sections} sections</div>";
            default:
                return "<div class='template-preview'>Template</div>";
        }
    }
}