@extends('layouts.app')

@section('title', 'Edit Site')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Site</h1>
                <p class="text-gray-600 mt-2">Update your website settings</p>
            </div>
            <a href="{{ route('sites.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                ← Back to Sites
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Site Info -->
            <div class="p-6 border-b">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9 3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">{{ $site->name }}</h2>
                        <p class="text-gray-500">{{ $site->subdomain }}.localhost</p>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('sites.update', $site) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Site Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Site Name
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $site->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="draft" 
                                       {{ $site->status === 'draft' ? 'checked' : '' }}
                                       class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2">Draft</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="published"
                                       {{ $site->status === 'published' ? 'checked' : '' }}
                                       class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2">Published</span>
                            </label>
                        </div>
                        <!-- Add to the status section -->
<div class="mt-6">
    <h3 class="text-lg font-semibold mb-4">Site Visibility</h3>
    
    @if($site->status === 'published')
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-green-800 font-medium">✓ Published</span>
                    <p class="text-green-600 text-sm mt-1">
                        Your site is publicly accessible at:
                        <code class="block mt-1 font-mono">http://{{ $site->subdomain }}.localhost</code>
                    </p>
                </div>
                <form action="{{ route('sites.unpublish', $site) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700"
                            onclick="return confirm('Unpublish this site? It will no longer be publicly accessible.')">
                        Unpublish Site
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-yellow-800 font-medium">Draft</span>
                    <p class="text-yellow-600 text-sm mt-1">
                        Your site is not publicly accessible yet.
                    </p>
                </div>
                <form action="{{ route('sites.publish', $site) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                            onclick="return confirm('Publish this site? It will be publicly accessible at: http://{{ $site->subdomain }}.localhost')">
                        Publish Site
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('sites.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Save Changes
                        </button>
                        <div class="flex space-x-3">
    <a href="{{ route('sites.pages.index', $site) }}" 
       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        📄 Manage Pages
    </a>
    <!-- ... existing buttons ... -->
</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection