@extends('layouts.app')

@section('title', 'Create Template')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Create New Template</h1>
                <p class="text-gray-600 mt-2">Save a page layout as a reusable template</p>
            </div>

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('templates.index') }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Templates
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('templates.store') }}" method="POST" id="template-form">
                @csrf
                
                <div class="space-y-6">
                    <!-- Template Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Template Name *
                        </label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Modern Hero, Business Homepage, Contact Layout..."
                               value="{{ old('name') }}">
                    </div>

                    <!-- Template Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Template Type *
                        </label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select type...</option>
                            <option value="hero" {{ old('type') == 'hero' ? 'selected' : '' }}>Hero Section</option>
                            <option value="content" {{ old('type') == 'content' ? 'selected' : '' }}>Content Section</option>
                            <option value="features" {{ old('type') == 'features' ? 'selected' : '' }}>Features Grid</option>
                            <option value="contact" {{ old('type') == 'contact' ? 'selected' : '' }}>Contact Section</option>
                            <option value="testimonial" {{ old('type') == 'testimonial' ? 'selected' : '' }}>Testimonials</option>
                            <option value="full_page" {{ old('type') == 'full_page' ? 'selected' : '' }}>Full Page Layout</option>
                            <option value="layout" {{ old('type') == 'layout' ? 'selected' : '' }}>Layout Container</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">
                            Full page templates replace entire pages, while sections are added to existing pages.
                        </p>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category *
                        </label>
                        <select id="category" name="category" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="business" {{ old('category') == 'business' ? 'selected' : '' }}>Business</option>
                            <option value="portfolio" {{ old('category') == 'portfolio' ? 'selected' : '' }}>Portfolio</option>
                            <option value="blog" {{ old('category') == 'blog' ? 'selected' : '' }}>Blog</option>
                            <option value="ecommerce" {{ old('category') == 'ecommerce' ? 'selected' : '' }}>E-commerce</option>
                        </select>
                    </div>

                    <!-- Visibility -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Visibility *
                        </label>
                        <div class="space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="visibility" value="private" 
                                       {{ old('visibility', 'private') == 'private' ? 'checked' : '' }}
                                       class="text-blue-600 focus:ring-blue-500" required>
                                <span class="ml-2">Private (Only you can see and use)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="visibility" value="public"
                                       {{ old('visibility') == 'public' ? 'checked' : '' }}
                                       class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2">Public (All users can use)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Source (Optional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Save from existing page (Optional)
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Site</label>
                                <select name="site_id" id="site_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded">
                                    <option value="">Select site...</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}" {{ $siteId == $site->id ? 'selected' : '' }}>
                                            {{ $site->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Page</label>
                                <select name="page_id" id="page_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded"
                                        {{ !$siteId ? 'disabled' : '' }}>
                                    <option value="">Select page...</option>
                                    @foreach($pages as $page)
                                        <option value="{{ $page->id }}" {{ $pageId == $page->id ? 'selected' : '' }}>
                                            {{ $page->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            If selected, the page content will be saved as this template.
                        </p>
                    </div>

                    <!-- Template Content (for manual creation) -->
                    <div id="manual-content" class="{{ $pageId ? 'hidden' : '' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Template Content
                        </label>
                        <div class="border border-gray-300 rounded-lg p-4">
                            <p class="text-gray-500 text-sm">
                                Content will be generated from the selected page, or you can create it manually in the editor.
                            </p>
                            <textarea id="content-editor" name="content" 
                                      class="w-full h-48 mt-2 px-3 py-2 border border-gray-300 rounded font-mono text-sm hidden">
                            </textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('templates.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Create Template
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const siteSelect = document.getElementById('site_id');
    const pageSelect = document.getElementById('page_id');
    const manualContentDiv = document.getElementById('manual-content');
    
    // Load pages when site is selected
    siteSelect.addEventListener('change', function() {
        const siteId = this.value;
        
        if (!siteId) {
            pageSelect.innerHTML = '<option value="">Select page...</option>';
            pageSelect.disabled = true;
            manualContentDiv.classList.remove('hidden');
            return;
        }
        
        // Enable page select
        pageSelect.disabled = false;
        
        // Fetch pages for this site
        fetch(`/api/sites/${siteId}/pages`)
            .then(response => response.json())
            .then(pages => {
                let options = '<option value="">Select page...</option>';
                pages.forEach(page => {
                    options += `<option value="${page.id}">${page.title}</option>`;
                });
                pageSelect.innerHTML = options;
                
                // Show/hide manual content
                manualContentDiv.classList.add('hidden');
            });
    });
    
    // When a page is selected, fetch its content
    pageSelect.addEventListener('change', function() {
        const pageId = this.value;
        
        if (!pageId) {
            document.getElementById('content-editor').classList.add('hidden');
            return;
        }
        
        // Fetch page content and populate the hidden textarea
        fetch(`/api/pages/${pageId}/content`)
            .then(response => response.json())
            .then(data => {
                const editor = document.getElementById('content-editor');
                editor.value = JSON.stringify(data, null, 2);
                editor.classList.remove('hidden');
            });
    });
    
    // Initialize if site is pre-selected
    if (siteSelect.value) {
        siteSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection