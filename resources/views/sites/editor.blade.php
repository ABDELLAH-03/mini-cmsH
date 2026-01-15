@extends('layouts.app')

@section('title', 'Editor - ' . $site->name)

@section('content')
<div class="h-screen flex flex-col">
    <!-- Top Bar -->
    <div class="bg-white border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3">
                <div class="flex items-center">
                    <h1 class="text-lg font-semibold text-gray-900">{{ $site->name }}</h1>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $site->subdomain }}.{{ config('app.domain', 'localhost') }}
                    </span>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click="previewMode = !previewMode"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <span x-text="previewMode ? 'Edit' : 'Preview'"></span>
                    </button>
                    <a href="{{ route('pages.index', $site) }}"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Pages
                    </a>
                    <button wire:click="save"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 bg-white border-r border-gray-200 overflow-y-auto">
            <div class="p-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">
                    Sections
                </h3>
                <div class="space-y-2">
                    @foreach(['hero', 'content', 'features', 'contact', 'testimonial'] as $type)
                    <button @click="addSection('{{ $type }}')"
                            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md flex items-center">
                        <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add {{ ucfirst($type) }}
                    </button>
                    @endforeach
                </div>

                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mt-6 mb-3">
                    Templates
                </h3>
                <div class="space-y-2">
                    @foreach($templates as $template)
                    <button @click="applyTemplate({{ $template->id }})"
                            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                        {{ $template->name }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Editor -->
        <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
            @livewire('page-editor', ['page' => $page])
        </div>

        <!-- Settings Panel -->
        <div class="w-80 bg-white border-l border-gray-200 overflow-y-auto p-4" 
             x-show="activeSection" 
             x-cloak>
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Section Settings</h3>
                    <p class="text-sm text-gray-500" x-text="getSectionType(activeSection)"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Background Color</label>
                    <input type="color" 
                           x-model="sections[activeSection].settings.background"
                           class="mt-1 block w-full">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Padding</label>
                    <input type="range" min="0" max="10" 
                           x-model="sections[activeSection].settings.padding"
                           class="mt-1 block w-full">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('editor', () => ({
            previewMode: false,
            activeSection: null,
            sections: @json($page->content['sections'] ?? []),
            
            init() {
                // Initialize Sortable
                const container = document.getElementById('sections-container');
                new Sortable(container, {
                    animation: 150,
                    onEnd: (evt) => {
                        const section = this.sections.splice(evt.oldIndex, 1)[0];
                        this.sections.splice(evt.newIndex, 0, section);
                        this.saveOrder();
                    }
                });
            },
            
            addSection(type) {
                const id = 'section_' + Date.now();
                this.sections.push({
                    id: id,
                    type: type,
                    content: this.getDefaultContent(type),
                    settings: {}
                });
                this.activeSection = id;
                this.save();
            },
            
            getDefaultContent(type) {
                const defaults = {
                    hero: { title: 'Hero Title', subtitle: 'Hero Subtitle', button_text: 'Learn More' },
                    content: { html: '<p>Your content here...</p>' },
                    features: { items: [{title: 'Feature 1', description: 'Desc 1'}] }
                };
                return defaults[type] || {};
            },
            
            applyTemplate(templateId) {
                fetch(`/templates/${templateId}/apply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ page_id: {{ $page->id }} })
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          location.reload();
                      }
                  });
            },
            
            save() {
                fetch(`/sites/{{ $site->id }}/pages/{{ $page->id }}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        content: { sections: this.sections },
                        _method: 'PUT'
                    })
                });
            },
            
            saveOrder() {
                const order = this.sections.map(s => s.id);
                fetch(`/sites/{{ $site->id }}/pages/order`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order: order })
                });
            },
            
            getSectionType(sectionId) {
                const section = this.sections.find(s => s.id === sectionId);
                return section ? section.type : '';
            }
        }));
    });
</script>
@endpush
@endsection