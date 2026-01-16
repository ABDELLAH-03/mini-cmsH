@extends('layouts.app')

@section('title', 'My Sites')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Sites</h1>
            <a href="{{ route('sites.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + New Site
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if($sites->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 mb-4">You haven't created any sites yet.</p>
            <a href="{{ route('sites.create') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Create Your First Site
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sites as $site)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">{{ $site->name }}</h3>
                            <p class="text-gray-500 text-sm">{{ $site->subdomain }}.localhost</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $site->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($site->status) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ $site->pages_count ?? 0 }} pages
                        </span>
                    </div>

                    <div class="flex space-x-2">
                        <a href="{{ route('sites.edit', $site) }}" 
                           class="flex-1 text-center py-2 text-sm text-gray-700 bg-gray-100 rounded hover:bg-gray-200">
                            Edit
                        </a>
                        <a href="{{ url('/sites/' . $site->subdomain) }}" target="_blank"
                           class="flex-1 text-center py-2 text-sm text-blue-700 bg-blue-100 rounded hover:bg-blue-200">
                            View
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection