<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TemplateController;
use App\Models\Page;
use App\Models\Site;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Homepage
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $sites = auth()->user()->sites()->latest()->get();
        return view('dashboard', compact('sites'));
    })->name('dashboard');

    Route::resource('sites', SiteController::class);
});
// Page routes within sites
Route::prefix('sites/{site}/pages')->name('sites.pages.')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('index');
    Route::get('/create', [PageController::class, 'create'])->name('create');
    Route::post('/', [PageController::class, 'store'])->name('store');
    Route::get('/{page}/edit', [PageController::class, 'edit'])->name('edit');
    Route::put('/{page}', [PageController::class, 'update'])->name('update');
    Route::delete('/{page}', [PageController::class, 'destroy'])->name('destroy');
    Route::post('/order', [PageController::class, 'updateOrder'])->name('order');
    Route::post('/{page}/set-homepage', [PageController::class, 'setHomepage'])->name('set-homepage');
});
// Template routes
Route::resource('templates', TemplateController::class)->except(['show']);
Route::post('/templates/apply', [TemplateController::class, 'apply'])->name('templates.apply');
Route::post('/sites/{site}/pages/{page}/save-template', [TemplateController::class, 'saveFromPage'])->name('templates.save-from-page');

// API routes for AJAX
Route::prefix('api')->group(function () {
    Route::get('/sites/{site}/pages', function (Site $site) {
        if ($site->user_id !== auth()->id()) {
            return response()->json([], 403);
        }
        return $site->pages()->select('id', 'title')->get();
    });

    Route::get('/pages/{page}/content', function (Page $page) {
        if ($page->site->user_id !== auth()->id()) {
            return response()->json([], 403);
        }
        return $page->content;
    });

    Route::get('/templates', [TemplateController::class, 'index']);
});
// Public site routes (subdomain based)
Route::domain('{subdomain}.' . env('DOMAIN', 'localhost'))->group(function () {
    Route::get('/', [PublicSiteController::class, 'showSite'])->name('public.site');
    Route::get('/{slug}', [PublicSiteController::class, 'showSite'])->name('public.page');
});

// Preview routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/sites/{site}/pages/{page}/preview', [PublicSiteController::class, 'preview'])->name('sites.pages.preview');
});

// API routes for live preview
Route::prefix('api')->group(function () {
    Route::get('/pages/{page}/last-modified', function (Page $page) {
        return response()->json([
            'last_modified' => strtotime($page->updated_at)
        ]);
    });

    Route::post('/page-view', function (Request $request) {
        // Track page views (simplified)
        Log::info('Page view', $request->all());
        return response()->json(['success' => true]);
    });
});
Route::post('/sites/{site}/pages/{page}/publish', [PageController::class, 'publish'])->name('sites.pages.publish');
Route::post('/sites/{site}/pages/{page}/unpublish', [PageController::class, 'unpublish'])->name('sites.pages.unpublish');
Route::post('/sites/{site}/publish', [SiteController::class, 'publish'])->name('sites.publish');
Route::post('/sites/{site}/unpublish', [SiteController::class, 'unpublish'])->name('sites.unpublish');