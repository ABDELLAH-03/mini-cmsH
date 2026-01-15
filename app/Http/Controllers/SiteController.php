<?

namespace App\Http\Controllers;

use App\Models\Site;
use App\Services\SiteBuilder;
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

        $site = SiteBuilder::createDefaultSite(Auth::user(), $request->all());

        return redirect()->route('sites.editor', $site)
            ->with('success', 'Site created successfully!');
    }

    public function editor(Site $site)
    {
        $this->authorize('update', $site);

        $pages = $site->pages()->orderBy('order')->get();
        $templates = \App\Models\Template::public()->get();

        return view('sites.editor', compact('site', 'pages', 'templates'));
    }

    public function update(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $site->update([
            'settings' => array_merge($site->settings ?? [], $request->settings)
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Site $site)
    {
        $this->authorize('delete', $site);
        $site->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Site deleted successfully!');
    }
}