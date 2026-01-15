<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\MediaController;

// Auth Routes (if using Laravel UI)
//Auth::routes();

// Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sites
    Route::resource('sites', SiteController::class);
    Route::get('sites/{site}/editor', [SiteController::class, 'editor'])->name('sites.editor');

    // Pages
    Route::prefix('sites/{site}/pages')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('pages.index');
        Route::post('/', [PageController::class, 'store'])->name('pages.store');
        Route::get('/{page}/editor', [PageController::class, 'editor'])->name('pages.editor');
        Route::put('/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::post('/order', [PageController::class, 'updateOrder'])->name('pages.order');
        Route::delete('/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
    });

    // Templates
    Route::resource('templates', TemplateController::class)->only(['index', 'store']);
    Route::post('templates/{template}/apply', [TemplateController::class, 'applyToPage'])->name('templates.apply');

    // Media
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media/upload', [MediaController::class, 'store'])->name('media.store');
});

// Public site routes (for published sites)
Route::domain('{subdomain}.' . config('app.domain', 'localhost'))->group(function () {
    Route::get('/', function ($subdomain) {
        $site = \App\Models\Site::where('subdomain', $subdomain)
            ->where('status', 'published')
            ->firstOrFail();

        $page = $site->homepage();

        if (!$page) {
            abort(404);
        }

        return view('site.public', [
            'site' => $site,
            'page' => $page
        ]);
    });

    Route::get('/{slug}', function ($subdomain, $slug) {
        $site = \App\Models\Site::where('subdomain', $subdomain)
            ->where('status', 'published')
            ->firstOrFail();

        $page = $site->pages()
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return view('site.public', [
            'site' => $site,
            'page' => $page
        ]);
    });
});

Route::get('/', function () {
    return view('welcome');});