<?

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Site $site)
    {
        $this->authorize('view', $site);

        $pages = $site->pages()->with('children')->orderBy('order')->get();

        return view('pages.index', compact('site', 'pages'));
    }

    public function store(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:pages,id'
        ]);

        $slug = Str::slug($request->title);
        $count = Page::where('site_id', $site->id)
            ->where('slug', 'like', "{$slug}%")
            ->count();

        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        $page = $site->pages()->create([
            'title' => $request->title,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'content' => ['sections' => []],
            'seo' => ['title' => $request->title]
        ]);

        return response()->json([
            'success' => true,
            'page' => $page,
            'redirect' => route('pages.editor', [$site, $page])
        ]);
    }

    public function editor(Site $site, Page $page)
    {
        $this->authorize('update', $site);

        $templates = \App\Models\Template::public()->get();

        return view('pages.editor', compact('site', 'page', 'templates'));
    }

    public function update(Request $request, Site $site, Page $page)
    {
        $this->authorize('update', $site);

        $page->update([
            'content' => $request->content,
            'seo' => $request->seo ?? $page->seo
        ]);

        return response()->json(['success' => true]);
    }

    public function updateOrder(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        foreach ($request->order as $order => $pageId) {
            Page::where('id', $pageId)
                ->where('site_id', $site->id)
                ->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Site $site, Page $page)
    {
        $this->authorize('delete', $page);

        if ($page->is_homepage) {
            return response()->json([
                'error' => 'Cannot delete homepage'
            ], 422);
        }

        $page->delete();

        return response()->json(['success' => true]);
    }
}