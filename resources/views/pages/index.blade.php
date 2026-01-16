@extends('layouts.app')

@section('title', 'Pages - ' . $site->name)

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pages</h1>
                <p class="text-gray-600 mt-2">Manage pages for {{ $site->name }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('sites.edit', $site) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    ← Back to Site
                </a>
                <a href="{{ route('sites.pages.create', $site) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    + New Page
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
        @endif

        <!-- Pages List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($pages->isEmpty())
            <div class="p-8 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 mb-4">No pages yet. Create your first page!</p>
                <a href="{{ route('sites.pages.create', $site) }}" 
                   class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Create First Page
                </a>
            </div>
            @else
            <div class="divide-y">
                @foreach($pages->whereNull('parent_id') as $page)
                    @include('pages.partials.page-item', ['page' => $page, 'depth' => 0])
                @endforeach
            </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Pages</h3>
                <p class="text-2xl font-bold">{{ $pages->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Homepage</h3>
                <p class="text-xl">
                    @if($pages->where('is_homepage', true)->first())
                        {{ $pages->where('is_homepage', true)->first()->title }}
                    @else
                        <span class="text-yellow-600">Not set</span>
                    @endif
                </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Site URL</h3>
                <p class="text-lg font-mono text-blue-600">
                    {{ $site->subdomain }}.localhost
                </p>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for drag & drop -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple confirmation for delete
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this page?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection