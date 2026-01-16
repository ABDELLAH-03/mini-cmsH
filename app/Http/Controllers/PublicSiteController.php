<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Page;
use Illuminate\Http\Request;

class PublicSiteController extends Controller
{
    /**
     * Handle public site requests via subdomain
     */
    public function showSite(Request $request, $subdomain = null)
    {
        // If no subdomain, show homepage or redirect
        if (!$subdomain) {
            if (auth()->check()) {
                return redirect()->route('dashboard');
            }
            return view('welcome');
        }

        // Find site by subdomain
        $site = Site::where('subdomain', $subdomain)
            ->where('status', 'published')
            ->firstOrFail();

        // Get homepage or requested page
        $slug = $request->segment(1) ?: 'home';

        $page = $site->pages()
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                    ->orWhere('is_homepage', true);
            })
            ->whereNotNull('published_at')
            ->firstOrFail();

        // Render the page
        return $this->renderPage($site, $page);
    }

    /**
     * Render a page with site theme
     */
    private function renderPage(Site $site, Page $page)
    {
        // Increment view count (only for public views, not previews)
        if (!request()->has('preview')) {
            $page->incrementViews();
        }

        $settings = $site->settings ?? [];
        $theme = $this->getThemeStyles($settings);

        return view('public.site', [
            'site' => $site,
            'page' => $page,
            'theme' => $theme,
            'navigation' => $this->getSiteNavigation($site)
        ]);
    }

    /**
     * Generate CSS theme from site settings
     */
    private function getThemeStyles($settings)
    {
        $primary = $settings['primary_color'] ?? '#3B82F6';
        $font = $settings['font_family'] ?? 'Inter';
        $container = $settings['container_width'] ?? '1200px';

        return "
            :root {
                --primary-color: {$primary};
                --font-family: '{$font}', sans-serif;
                --container-width: {$container};
            }
            
            body {
                font-family: var(--font-family);
            }
            
            .btn-primary {
                background-color: var(--primary-color);
            }
            
            .container {
                max-width: var(--container-width);
            }
        ";
    }

    /**
     * Get site navigation menu
     */
    private function getSiteNavigation(Site $site)
    {
        return $site->pages()
            ->whereNull('parent_id')
            ->whereNotNull('published_at')
            ->orderBy('order')
            ->get()
            ->map(function ($page) {
                return [
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'is_homepage' => $page->is_homepage
                ];
            });
    }

    /**
     * Live preview for editors
     */
    /**
     * Live preview for editors
     */
    public function preview(Request $request, Site $site, Page $page)
    {
        // Only site owner can preview
        if ($site->user_id !== auth()->id()) {
            abort(403);
        }

        // Use draft content if provided
        $content = $request->get('content') ? json_decode($request->get('content'), true) : $page->content;

        // Get site settings for theme
        $settings = $site->settings ?? [];
        $theme = $this->getThemeStyles($settings);

        // Get navigation (even unpublished pages for preview)
        $navigation = $site->pages()
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get()
            ->map(function ($page) {
                return [
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'is_homepage' => $page->is_homepage
                ];
            });

        return view('public.preview', [
            'site' => $site,
            'page' => $page,
            'content' => $content,
            'theme' => $theme,
            'navigation' => $navigation,
            'is_preview' => true
        ]);
    }
    
}