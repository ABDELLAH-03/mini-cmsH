@extends('layouts.app')

@section('title', 'Editing: ' . $page->title)

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Editor Header -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Editing: {{ $page->title }}</h1>
                    <p class="text-sm text-gray-600">
                        {{ $site->name }} • http://{{ $site->subdomain }}.localhost/{{ $page->slug }}
                    </p>
                </div>
                
                <div class="flex items-center space-x-3">
                    <!-- Save Button -->
                    <button id="save-button"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                        <svg id="save-icon" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span id="save-text">Save</span>
                    </button>
                    
                    <!-- Preview Button -->
<a href="{{ route('sites.pages.preview', [$site, $page]) }}" 
   target="_blank"
   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
    👁️ Preview
</a>
                    
                    <!-- Back to Pages -->
                    <a href="{{ route('sites.pages.index', $site) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        ← Pages
                    </a>
                </div>
                @if($page->published_at)
    <form action="{{ route('sites.pages.unpublish', [$site, $page]) }}" method="POST" class="inline">
        @csrf
        <button type="submit" 
                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700"
                onclick="return confirm('Unpublish this page?')">
            ⏸️ Unpublish
        </button>
    </form>
    <span class="text-green-600 ml-2">✓ Published</span>
@else
    <form action="{{ route('sites.pages.publish', [$site, $page]) }}" method="POST" class="inline">
        @csrf
        <button type="submit" 
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            🚀 Publish
        </button>
    </form>
    <span class="text-gray-500 ml-2">Draft</span>
@endif
                
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar - Components -->
           <!-- Left Sidebar - Components & Templates -->
<div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow p-4 mb-4">
        <h3 class="font-medium text-gray-900 mb-4">Add Section</h3>
        
        <div class="space-y-3">
            <button onclick="addSection('hero')"
                    class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="font-medium">Hero Section</div>
                <div class="text-sm text-gray-500">Large header with title and button</div>
            </button>
            
            <button onclick="addSection('content')"
                    class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="font-medium">Content Section</div>
                <div class="text-sm text-gray-500">Rich text content area</div>
            </button>
            
            <button onclick="addSection('features')"
                    class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="font-medium">Features Grid</div>
                <div class="text-sm text-gray-500">List of features with icons</div>
            </button>
            
            <button onclick="addSection('contact')"
                    class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="font-medium">Contact Form</div>
                <div class="text-sm text-gray-500">Contact information and form</div>
            </button>
            
        </div>
    </div>
    
    <!-- Template Library -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-medium text-gray-900">Templates</h3>
            <a href="{{ route('templates.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800">
                View All
            </a>
        </div>
        
        <div id="template-list" class="space-y-3">
            <!-- Templates will be loaded here -->
            <div class="text-center py-4">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div>
                <p class="text-sm text-gray-500 mt-2">Loading templates...</p>
            </div>
        </div>
        
        <!-- Save as Template Button -->
        <div class="mt-6 pt-6 border-t">
            <button onclick="showSaveTemplateModal()"
        class="w-full px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:opacity-90">
    💾 Save Page as Template
</button>
        </div>
    </div>
</div>

            <!-- Main Editor Area -->
            <div class="lg:col-span-2">
                <div id="sections-container" class="space-y-4 min-h-screen">
                    <!-- Sections will be dynamically added here -->
                    @if(empty($page->content['sections']))
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 mb-4">No sections yet. Add your first section!</p>
                            <button onclick="addSection('content')"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Add First Section
                            </button>
                        </div>
                    @else
                        @foreach($page->content['sections'] as $index => $section)
                            @include('pages.partials.section-editor', ['section' => $section, 'index' => $index])
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Right Sidebar - Settings -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="font-medium text-gray-900 mb-4">Section Settings</h3>
                    
                    <div id="section-settings" class="space-y-4">
                        <div class="text-gray-500 text-center py-8">
                            Select a section to edit its settings
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Editor Template (Hidden) -->
<template id="section-template">
    @include('pages.partials.section-editor', ['section' => [
        'id' => 'template-id',
        'type' => 'template-type',
        'content' => []
    ], 'index' => 'template-index'])
</template>

<script>
// Page content structure
let pageContent = @json($page->content['sections'] ?? []);
let activeSectionId = null;

// Add a new section
function addSection(type) {
    const sectionId = 'section_' + Date.now();
    const section = {
        id: sectionId,
        type: type,
        content: getDefaultContent(type),
        settings: {}
    };
    
    pageContent.push(section);
    renderSections();
    setActiveSection(sectionId);
    savePage();
}

// Get default content for section type
function getDefaultContent(type) {
    const defaults = {
        hero: {
            title: 'Welcome to Our Website',
            subtitle: 'This is a hero section. Edit the content to make it your own.',
            button_text: 'Learn More',
            button_link: '#'
        },
        content: {
            html: '<p>Start writing your content here. You can add text, images, and more.</p>'
        },
        features: {
            items: [
                { title: 'Feature 1', description: 'Description for feature 1' },
                { title: 'Feature 2', description: 'Description for feature 2' }
            ]
        },
        contact: {
            title: 'Contact Us',
            email: 'hello@example.com',
            phone: '+1 234 567 890'
        }
    };
    
    return defaults[type] || {};
}

// Render all sections
function renderSections() {
    const container = document.getElementById('sections-container');
    container.innerHTML = '';
    
    if (pageContent.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 mb-4">No sections yet. Add your first section!</p>
                <button onclick="addSection('content')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add First Section
                </button>
            </div>
        `;
        return;
    }
    
    pageContent.forEach((section, index) => {
        const template = document.getElementById('section-template');
        const clone = template.content.cloneNode(true);
        
        // Update section data
        const sectionElement = clone.querySelector('.section-editor');
        sectionElement.dataset.sectionId = section.id;
        
        // Set section type badge
        const typeBadge = clone.querySelector('.section-type');
        typeBadge.textContent = section.type.charAt(0).toUpperCase() + section.type.slice(1);
        
        // Set content in inputs
        if (section.type === 'hero') {
            clone.querySelector('[name="hero-title"]').value = section.content.title || '';
            clone.querySelector('[name="hero-subtitle"]').value = section.content.subtitle || '';
            clone.querySelector('[name="hero-button-text"]').value = section.content.button_text || '';
            clone.querySelector('[name="hero-button-link"]').value = section.content.button_link || '';
        } else if (section.type === 'content') {
            clone.querySelector('[name="content-html"]').value = section.content.html || '';
        }
        
        container.appendChild(clone);
    });
}

// Set active section
function setActiveSection(sectionId) {
    activeSectionId = sectionId;
    const section = pageContent.find(s => s.id === sectionId);
    
    if (!section) return;
    
    const settingsPanel = document.getElementById('section-settings');
    settingsPanel.innerHTML = `
        <div>
            <h4 class="font-medium mb-2">${section.type.charAt(0).toUpperCase() + section.type.slice(1)} Settings</h4>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Background Color</label>
                    <input type="color" 
                           value="${section.settings.background || '#ffffff'}"
                           onchange="updateSectionSetting('${sectionId}', 'background', this.value)"
                           class="w-full">
                </div>
                
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Padding</label>
                    <input type="range" min="0" max="10" 
                           value="${section.settings.padding || 4}"
                           onchange="updateSectionSetting('${sectionId}', 'padding', this.value)"
                           class="w-full">
                    <div class="text-xs text-gray-500 text-right">${section.settings.padding || 4}rem</div>
                </div>
                
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Text Alignment</label>
                    <select onchange="updateSectionSetting('${sectionId}', 'textAlign', this.value)"
                            class="w-full px-2 py-1 border border-gray-300 rounded">
                        <option value="left" ${(section.settings.textAlign || 'left') === 'left' ? 'selected' : ''}>Left</option>
                        <option value="center" ${(section.settings.textAlign || 'left') === 'center' ? 'selected' : ''}>Center</option>
                        <option value="right" ${(section.settings.textAlign || 'left') === 'right' ? 'selected' : ''}>Right</option>
                    </select>
                </div>
            </div>
        </div>
    `;
}

// Update section setting
function updateSectionSetting(sectionId, key, value) {
    const section = pageContent.find(s => s.id === sectionId);
    if (section) {
        if (!section.settings) section.settings = {};
        section.settings[key] = value;
        savePage();
    }
}

// Update section content
function updateSectionContent(sectionId, field, value) {
    const section = pageContent.find(s => s.id === sectionId);
    if (section) {
        // Handle nested fields (e.g., items.0.title)
        if (field.includes('.')) {
            const parts = field.split('.');
            let obj = section.content;
            for (let i = 0; i < parts.length - 1; i++) {
                if (!obj[parts[i]]) obj[parts[i]] = {};
                obj = obj[parts[i]];
            }
            obj[parts[parts.length - 1]] = value;
        } else {
            section.content[field] = value;
        }
        savePage();
    }
}

// Delete a section
function deleteSection(sectionId) {
    if (confirm('Delete this section?')) {
        pageContent = pageContent.filter(s => s.id !== sectionId);
        renderSections();
        savePage();
    }
}

// Save page to server
function savePage() {
    const saveButton = document.getElementById('save-button');
    const saveIcon = document.getElementById('save-icon');
    const saveText = document.getElementById('save-text');
    
    // Show saving state
    saveButton.disabled = true;
    saveIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
    saveText.textContent = 'Saving...';
    
    // Prepare data
    const data = {
        content: { sections: pageContent },
        seo: {
            title: document.getElementById('seo-title').value,
            description: document.getElementById('seo-description').value
        }
    };
    
    // Send to server
    fetch('{{ route("sites.pages.update", [$site, $page]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        // Show success state
        saveIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
        saveText.textContent = 'Saved!';
        
        setTimeout(() => {
            saveText.textContent = 'Save';
            saveButton.disabled = false;
        }, 2000);
    })
    .catch(error => {
        console.error('Error:', error);
        saveIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
        saveText.textContent = 'Error';
        
        setTimeout(() => {
            saveText.textContent = 'Save';
            saveButton.disabled = false;
        }, 2000);
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderSections();
    
    // Setup save button
    document.getElementById('save-button').addEventListener('click', savePage);
    
    // Auto-save on input (debounced)
    let saveTimeout;
    document.addEventListener('input', function(e) {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(savePage, 2000);
    });
});
// Load templates for sidebar
function loadTemplates() {
    fetch('{{ route("templates.index") }}?ajax=1')
        .then(response => response.text())
        .then(html => {
            document.getElementById('template-list').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading templates:', error);
            document.getElementById('template-list').innerHTML = `
                <div class="text-center py-4 text-red-600">
                    Failed to load templates
                </div>
            `;
        });
}

// Apply template to current page
// Apply template to current page
function applyTemplate(templateId, isFullPage = false) {
    if (isFullPage && !confirm('This will replace all current sections. Continue?')) {
        return;
    }
    
    // Show loading
    const templateList = document.getElementById('template-list');
    const originalContent = templateList.innerHTML;
    templateList.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div><p class="text-sm text-gray-500 mt-2">Applying template...</p></div>';
    
    // Get current site and page IDs
    const siteId = {{ $site->id }};
    const pageId = {{ $page->id }};
    
    fetch('{{ route("templates.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            template_id: templateId,
            site_id: siteId,
            page_id: pageId,
            full_page: isFullPage
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw new Error(err.error || 'Network error'); });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification(data.message, 'success');
            
            if (isFullPage || data.template_type === 'full_page') {
                // Reload page for full page templates
                setTimeout(() => location.reload(), 1000);
            } else {
                // Add section for regular templates
                addSectionFromTemplate(data.template_type, data.template_content, templateId);
                // Refresh template list
                loadTemplates();
            }
        } else {
            throw new Error(data.message || 'Failed to apply template');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error: ' + error.message, 'error');
        templateList.innerHTML = originalContent;
    });
}

// Add section from template
function addSectionFromTemplate(type, content, templateId) {
    const sectionId = 'template_' + templateId + '_' + Date.now();
    const section = {
        id: sectionId,
        type: type,
        content: content,
        template_id: templateId,
        settings: {}
    };
    
    pageContent.push(section);
    renderSections();
    savePage();
}

// Show notification
function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-100 border-green-400 text-green-700',
        error: 'bg-red-100 border-red-400 text-red-700',
        info: 'bg-blue-100 border-blue-400 text-blue-700'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 border px-6 py-4 rounded-lg shadow-lg ${colors[type]} max-w-md`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span class="mr-3">${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="ml-4 text-gray-500 hover:text-gray-700">
                ×
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Save current page as template
// Save current page as template
function showSaveTemplateModal() {
    // Create modal HTML
    const modalHtml = `
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Save Page as Template</h3>
                    
                    <form id="save-template-form">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Template Name</label>
                                <input type="text" name="name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded"
                                       placeholder="My Awesome Template">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select name="type" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded">
                                    <option value="full_page">Full Page Layout</option>
                                    <option value="hero">Hero Section</option>
                                    <option value="content">Content Section</option>
                                    <option value="features">Features Grid</option>
                                    <option value="contact">Contact Section</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded">
                                    <option value="general">General</option>
                                    <option value="business">Business</option>
                                    <option value="portfolio">Portfolio</option>
                                    <option value="blog">Blog</option>
                                    <option value="ecommerce">E-commerce</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                                <div class="space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="visibility" value="private" checked
                                               class="text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2">Private (only you)</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="visibility" value="public"
                                               class="text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2">Public (all users)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" onclick="this.closest('.fixed.inset-0').remove()"
                                    class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Save Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Create and append modal
    const modal = document.createElement('div');
    modal.innerHTML = modalHtml;
    document.body.appendChild(modal);
    
    // Handle form submission
    const form = modal.querySelector('#save-template-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Saving...';
        submitButton.disabled = true;
        
        const formData = new FormData(this);
        formData.append('content', JSON.stringify({ sections: pageContent }));
        
        fetch('{{ route("templates.save-from-page", [$site, $page]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Template saved successfully!', 'success');
                modal.remove();
                loadTemplates(); // Refresh template list
            } else {
                throw new Error(data.message || 'Failed to save template');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error: ' + error.message, 'error');
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        });
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.remove();
        }
    });
}

function closeSaveTemplateModal() {
    const modal = document.querySelector('.fixed.inset-0');
    if (modal) {
        modal.remove();
    }
}

// Load templates when editor loads
loadTemplates();
// Live preview functionality
let previewWindow = null;

// Open preview in new window
function openLivePreview() {
    const previewUrl = '{{ route("sites.pages.preview", [$site, $page]) }}';
    previewWindow = window.open(previewUrl, 'preview_{{ $page->id }}', 'width=1200,height=800');
    
    if (previewWindow) {
        // Send initial content
        updatePreview();
    }
}

// Update preview window with current content
function updatePreview() {
    if (!previewWindow || previewWindow.closed) return;
    
    const content = {
        sections: pageContent,
        title: document.getElementById('seo-title')?.value || '{{ $page->title }}'
    };
    
    // This would typically use WebSockets, but for simplicity we'll use a form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("sites.pages.preview", [$site, $page]) }}';
    form.target = 'preview_{{ $page->id }}';
    form.style.display = 'none';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'content';
    input.value = JSON.stringify(content);
    
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    
    form.appendChild(input);
    form.appendChild(csrf);
    document.body.appendChild(form);
    
    // Store preview window reference
    window.previewWindows = window.previewWindows || {};
    window.previewWindows['{{ $page->id }}'] = previewWindow;
    
    form.submit();
    form.remove();
}

// Auto-update preview on save
function savePage() {
    const saveButton = document.getElementById('save-button');
    const saveIcon = document.getElementById('save-icon');
    const saveText = document.getElementById('save-text');
    
    // Show saving state
    saveButton.disabled = true;
    saveIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
    saveText.textContent = 'Saving...';
    
    // Prepare data
    const data = {
        content: { sections: pageContent },
        seo: {
            title: document.getElementById('seo-title').value,
            description: document.getElementById('seo-description').value
        }
    };
    
    // Send to server
    fetch('{{ route("sites.pages.update", [$site, $page]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        // Show success state
        saveIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
        saveText.textContent = 'Saved!';
        
        // Update preview if open
        updatePreview();
        
        setTimeout(() => {
            saveText.textContent = 'Save';
            saveButton.disabled = false;
        }, 2000);
    })
    .catch(error => {
        console.error('Error:', error);
        saveIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
        saveText.textContent = 'Error';
        
        setTimeout(() => {
            saveText.textContent = 'Save';
            saveButton.disabled = false;
        }, 2000);
    });
}

// Auto-save every 30 seconds
setInterval(() => {
    if (pageContent.length > 0 && document.hasFocus()) {
        console.log('Auto-saving...');
        savePage();
    }
}, 30000);
</script>
@endsection