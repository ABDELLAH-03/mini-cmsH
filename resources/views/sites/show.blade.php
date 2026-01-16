@extends('layouts.app')

@section('title', 'View Site')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Site Overview</h1>
            <div class="flex space-x-4">
                <a href="{{ route('sites.edit', $site) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Edit Site
                </a>
                <a href="{{ route('sites.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Back to Sites
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Site Info Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-4 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9 3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">{{ $site->name }}</h2>
                            <p class="text-gray-600">http://{{ $site->subdomain }}.localhost</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <span class="inline-block mt-1 px-3 py-1 text-sm rounded-full 
                                {{ $site->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($site->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Created</h3>
                            <p class="mt-1">{{ $site->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                {{-- <div class="mt-6 bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="#" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
                            <svg class="w-6 h-6 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Add Page</span>
                        </a>
                        <a href="#" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
                            <svg class="w-6 h-6 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            <span>View Site</span>
                        </a>
                    </div>
                </div> --}}
                <div class="mt-6 bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('sites.pages.create', $site) }}" 
           class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
            <svg class="w-6 h-6 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Add Page</span>
        </a>
        <a href="{{ route('sites.pages.index', $site) }}" 
           class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-center">
            <svg class="w-6 h-6 mx-auto mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            <span>View All Pages</span>
        </a>
    </div>
</div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Site Settings</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Primary Color</h4>
                            <div class="flex items-center mt-1">
                                <div class="w-6 h-6 rounded-full mr-2" 
                                     style="background-color: {{ $site->settings['primary_color'] ?? '#3B82F6' }}"></div>
                                <span>{{ $site->settings['primary_color'] ?? '#3B82F6' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Font Family</h4>
                            <p class="mt-1">{{ $site->settings['font_family'] ?? 'Inter' }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Container Width</h4>
                            <p class="mt-1">{{ $site->settings['container_width'] ?? '1200px' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">Danger Zone</h3>
                    <p class="text-red-600 text-sm mb-4">Deleting a site cannot be undone.</p>
                    <form action="{{ route('sites.destroy', $site) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this site?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Delete Site
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection