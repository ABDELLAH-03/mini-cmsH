@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back!</h1>
            <p class="text-gray-600 mt-2">Manage your websites and content.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Quick Stats -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Quick Stats</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-500">Total Sites</p>
                            <p class="text-2xl font-bold">{{ $sites->count() }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Published Sites</p>
                            <p class="text-2xl font-bold">{{ $sites->where('status', 'published')->count() }}</p>
                        </div>
                        <a href="{{ route('sites.create') }}" 
                           class="block w-full mt-6 text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                            + New Site
                        </a>
                    </div>
                </div>
            </div>
            <!-- Add to dashboard after quick stats -->
<div class="mt-8 bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4">📊 Site Analytics</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-4 border rounded-lg">
            <div class="text-2xl font-bold text-blue-600">
                {{ auth()->user()->sites()->sum('views') }}
            </div>
            <div class="text-sm text-gray-600 mt-1">Total Views</div>
        </div>
        
        <div class="text-center p-4 border rounded-lg">
            <div class="text-2xl font-bold text-green-600">
                {{ auth()->user()->sites()->where('status', 'published')->count() }}
            </div>
            <div class="text-sm text-gray-600 mt-1">Published Sites</div>
        </div>
        
        <div class="text-center p-4 border rounded-lg">
            <div class="text-2xl font-bold text-purple-600">
                {{ auth()->user()->pages()->whereNotNull('published_at')->count() }}
            </div>
            <div class="text-sm text-gray-600 mt-1">Published Pages</div>
        </div>
    </div>
</div>

            <!-- Sites List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold">Your Sites</h2>
                    </div>
                    
                    @if($sites->isEmpty())
                    <div class="p-8 text-center">
                        <p class="text-gray-500 mb-4">No sites yet. Create your first website!</p>
                        <a href="{{ route('sites.create') }}" 
                           class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Create First Site
                        </a>
                    </div>
                    @else
                    <div class="divide-y">
                        @foreach($sites as $site)
                        <div class="p-6 flex items-center justify-between hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9 3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">{{ $site->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $site->subdomain }}.localhost</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 text-xs rounded-full 
                                    {{ $site->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($site->status) }}
                                </span>
                                <a href="{{ route('sites.edit', $site) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    Manage
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection