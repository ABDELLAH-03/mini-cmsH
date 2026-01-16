@extends('layouts.app')

@section('title', 'Create Page - ' . $site->name)

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Create New Page</h1>
                <p class="text-gray-600 mt-2">Add a page to {{ $site->name }}</p>
            </div>

            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('sites.pages.index', $site) }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Pages
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('sites.pages.store', $site) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Page Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Page Title *
                        </label>
                        <input type="text" id="title" name="title" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="About Us, Contact, Services..."
                               value="{{ old('title') }}">
                        <p class="mt-1 text-sm text-gray-500">
                            This will appear in navigation and as the page heading.
                        </p>
                    </div>

                    <!-- Parent Page -->
                    @if($parentPages->count() > 0)
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Parent Page (Optional)
                        </label>
                        <select id="parent_id" name="parent_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- No parent (top level) --</option>
                            @foreach($parentPages as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->title }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">
                            Select a parent page to create a nested page structure.
                        </p>
                    </div>
                    @endif

                    <!-- Preview -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">URL Preview</h4>
                        <p class="text-sm text-gray-600">
                            Your page will be accessible at:
                            <br>
                            <code class="text-blue-600 mt-1" id="url-preview">
                                http://{{ $site->subdomain }}.localhost/<span id="slug-preview">page-title</span>
                            </code>
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('sites.pages.index', $site) }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Create Page
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const slugPreview = document.getElementById('slug-preview');
    
    // Generate slug from title
    function generateSlug(text) {
        return text.toLowerCase()
            .replace(/[^\w\s]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
    
    // Update slug preview
    titleInput.addEventListener('input', function() {
        const slug = generateSlug(this.value) || 'page-title';
        slugPreview.textContent = slug;
    });
    
    // Initialize on page load
    if (titleInput.value) {
        slugPreview.textContent = generateSlug(titleInput.value) || 'page-title';
    }
});
</script>
@endsection