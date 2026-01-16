@extends('layouts.app')

@section('title', 'Create New Site')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6">Create New Website</h1>
            
            <form action="{{ route('sites.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Site Name
                    </label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="My Awesome Website">
                </div>

                <div class="mb-6">
                    <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-2">
                        Subdomain
                    </label>
                    <div class="flex items-center">
                        <input type="text" id="subdomain" name="subdomain" required
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="my-site">
                        <span class="px-4 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-gray-600">
                            .localhost
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        This will be your site's URL: http://<span id="url-preview" class="font-semibold">my-site</span>.localhost
                    </p>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('sites.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Create Site
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Live URL preview
    document.getElementById('subdomain').addEventListener('input', function(e) {
        document.getElementById('url-preview').textContent = e.target.value || 'my-site';
    });
</script>
@endsection