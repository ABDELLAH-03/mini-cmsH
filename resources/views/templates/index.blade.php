@extends('layouts.app')

@section('title', 'Template Library')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Template Library</h1>
                <p class="text-gray-600 mt-2">Reusable designs for your pages</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('templates.create') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    + New Template
                </a>
            </div>
        </div>

        <!-- Category Filter -->
        <div class="mb-8">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('templates.index', ['category' => 'all']) }}"
                   class="px-4 py-2 rounded-lg {{ $selectedCategory === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All Templates
                </a>
                @foreach($categories as $category)
                <a href="{{ route('templates.index', ['category' => $category]) }}"
                   class="px-4 py-2 rounded-lg {{ $selectedCategory === $category ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ ucfirst($category) }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
        @endif

        <!-- Templates Grid -->
        @if($templates->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No templates yet</h3>
            <p class="text-gray-500 mb-6">Create your first template to speed up your workflow.</p>
            <a href="{{ route('templates.create') }}" 
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Create First Template
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($templates as $template)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                <!-- Template Preview -->
                <div class="p-4 border-b">
                    <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
                        {!! $template->preview_html !!}
                    </div>
                </div>

                <!-- Template Info -->
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $template->name }}</h3>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $template->type === 'full_page' ? 'bg-purple-100 text-purple-800' : 
                                       ($template->type === 'hero' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($template->type) }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $template->usage_count }} uses
                                </span>
                            </div>
                        </div>
                        
                        @if($template->visibility === 'system')
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">System</span>
                        @elseif($template->visibility === 'public')
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Public</span>
                        @else
                            <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">Private</span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        {{ $template->category }} • 
                        @if($template->user_id === auth()->id())
                            Your template
                        @else
                            By {{ $template->user->name ?? 'System' }}
                        @endif
                    </p>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        @if($template->type === 'full_page')
                        <button onclick="applyFullPageTemplate({{ $template->id }})"
                                class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            Apply Full Page
                        </button>
                        @else
                        <button onclick="applySectionTemplate({{ $template->id }})"
                                class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            Add Section
                        </button>
                        @endif
                        
                        @if($template->user_id === auth()->id())
                        <form action="{{ route('templates.destroy', $template) }}" method="POST" 
                              onsubmit="return confirm('Delete this template?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $templates->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Apply Template Modal -->
<div id="apply-template-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Apply Template</h3>
                <p class="text-gray-600 mb-6">Select a site and page to apply this template to.</p>
                
                <form id="apply-template-form">
                    @csrf
                    <input type="hidden" name="template_id" id="modal-template-id">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Site</label>
                            <select name="site_id" id="site-select" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded" required>
                                <option value="">Choose a site...</option>
                                @foreach(auth()->user()->sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select Page</label>
                            <select name="page_id" id="page-select" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded" required>
                                <option value="">Choose a page...</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeApplyModal()"
                                class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Apply Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let selectedTemplateId = null;

// Open apply modal for full page templates
function applyFullPageTemplate(templateId) {
    selectedTemplateId = templateId;
    document.getElementById('modal-template-id').value = templateId;
    document.getElementById('apply-template-modal').classList.remove('hidden');
}

// Open apply modal for section templates
function applySectionTemplate(templateId) {
    // For now, just show a message
    alert('To apply a section template, go to a page editor and use the "Templates" sidebar.');
}

// Close apply modal
function closeApplyModal() {
    document.getElementById('apply-template-modal').classList.add('hidden');
    selectedTemplateId = null;
}

// Load pages when site is selected
document.getElementById('site-select').addEventListener('change', function() {
    const siteId = this.value;
    const pageSelect = document.getElementById('page-select');
    
    if (!siteId) {
        pageSelect.innerHTML = '<option value="">Choose a page...</option>';
        return;
    }
    
    // Fetch pages for this site
    fetch(`/api/sites/${siteId}/pages`)
        .then(response => response.json())
        .then(pages => {
            let options = '<option value="">Choose a page...</option>';
            pages.forEach(page => {
                options += `<option value="${page.id}">${page.title}</option>`;
            });
            pageSelect.innerHTML = options;
        });
});

// Handle form submission
document.getElementById('apply-template-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("templates.apply") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeApplyModal();
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to apply template');
    });
});

// Close modal when clicking outside
document.getElementById('apply-template-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApplyModal();
    }
});
</script>
@endsection