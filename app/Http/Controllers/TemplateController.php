<?

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::public()->latest()->get();
        $userTemplates = Auth::user()->templates()->latest()->get();

        return view('templates.index', compact('templates', 'userTemplates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:hero,features,contact,testimonial,full_page,layout',
            'content' => 'required|array'
        ]);

        $template = Auth::user()->templates()->create([
            'name' => $request->name,
            'type' => $request->type,
            'content' => $request->content,
            'category' => $request->category ?? 'general',
            'is_public' => $request->boolean('is_public', false)
        ]);

        return response()->json([
            'success' => true,
            'template' => $template
        ]);
    }

    public function applyToPage(Request $request, Site $site, Page $page)
    {
        $this->authorize('update', $site);

        $template = Template::findOrFail($request->template_id);

        $newContent = $page->content;
        $newContent['sections'][] = [
            'id' => uniqid(),
            'type' => $template->type,
            'content' => $template->content['content'] ?? [],
            'template_id' => $template->id
        ];

        $page->update(['content' => $newContent]);

        return response()->json(['success' => true]);
    }
}