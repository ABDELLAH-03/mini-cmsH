@extends('layouts.app')

@section('title', 'Editing: ' . $page->title)

@section('head')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <style>
        .ckeditor-container {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .ck-editor__editable {
            min-height: 200px;
            max-height: 500px;
            overflow-y: auto;
            padding: 1rem;
        }

        /* Dark mode support */
        .dark .ck-editor {
            --ck-color-base-background: #1a202c;
            --ck-color-text: #e2e8f0;
            --ck-color-base-border: #4a5568;
        }
    </style>
@endsection

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
                </div>
                
                <div class="flex items-center space-x-3">
                    <!-- Preview Button -->
                    <a href="{{ route('sites.pages.preview', [$site, $page]) }}" 
                       target="_blank"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        👁️ Preview
                    </a>
                    
                    <!-- Publish/Unpublish Button -->
                    @if($page->published_at)
                        <form action="{{ route('sites.pages.unpublish', [$site, $page]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700"
                                    onclick="return confirm('Unpublish this page? It will no longer be publicly accessible.')">
                                ⏸️ Unpublish
                            </button>
                        </form>
                        <span class="text-green-600 text-sm">
                            ✓ Published {{ $page->published_at->diffForHumans() }}
                        </span>
                    @else
                        <form action="{{ route('sites.pages.publish', [$site, $page]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                                    onclick="return confirm('Publish this page? It will be publicly accessible.')">
                                🚀 Publish
                            </button>
                        </form>
                        <span class="text-gray-500 text-sm">Draft</span>
                    @endif
                    
                    <!-- Back to Pages -->
                    <a href="{{ route('sites.pages.index', $site) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        ← Pages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar - Components -->
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
                        <div class="text-center py-4">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="text-sm text-gray-500 mt-2">Loading templates...</p>
                        </div>
                    </div>
                    
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

<!-- Debug buttons -->
<div class="fixed bottom-4 right-4 z-50 space-y-2">
    <button onclick="debugShowSections()" 
            class="bg-blue-500 text-white p-2 rounded text-sm">
        🔍 Show Sections
    </button>
    <button onclick="deleteFirstSection()" 
            class="bg-red-500 text-white p-2 rounded text-sm">
        🧪 Delete First
    </button>
</div>

<script>
// ==================== GLOBAL VARIABLES ====================
let pageContent = @json($page->content['sections'] ?? []);
let activeSectionId = null;
let ckeditorInstances = {};

// ==================== CKEDITOR FUNCTIONS ====================
function initCKEditor(textareaId, content = '') {
    if (ckeditorInstances[textareaId]) {
        return ckeditorInstances[textareaId];
    }

    const textarea = document.getElementById(textareaId);
    if (!textarea) return null;

    return ClassicEditor
        .create(textarea, {
            toolbar: [
                'heading', '|', 
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ],
            placeholder: 'Start typing...',
            initialData: content
        })
        .then(editor => {
            ckeditorInstances[textareaId] = editor;
            
            editor.model.document.on('change:data', () => {
                updateSectionFromCKEditor(textareaId);
            });
            
            return editor;
        })
        .catch(console.error);
}

function updateSectionFromCKEditor(textareaId) {
    const editor = ckeditorInstances[textareaId];
    if (!editor) return;
    
    const sectionId = textareaId.replace('editor-', '');
    const content = editor.getData();
    
    const section = pageContent.find(s => s.id === sectionId);
    if (section && section.type === 'content') {
        section.content.html = content;
        clearTimeout(window.ckSaveTimeout);
        window.ckSaveTimeout = setTimeout(savePage, 1500);
    }
}

// ==================== SECTION MANAGEMENT ====================
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

// ==================== DELETE FUNCTION (FIXED) ====================
function deleteSection(sectionId) {
    console.log('🗑️ DELETE SECTION called with REAL ID:', sectionId);
    console.log('Current sections:', pageContent.map(s => s.id));
    
    if (!confirm('Are you sure you want to delete this section?')) {
        return;
    }
    
    // 1. Clean up CKEditor
    if (ckeditorInstances['editor-' + sectionId]) {
        try {
            ckeditorInstances['editor-' + sectionId].destroy();
            delete ckeditorInstances['editor-' + sectionId];
        } catch (e) {
            console.warn('CKEditor cleanup failed:', e);
        }
    }
    
    // 2. Remove from pageContent array
    const originalLength = pageContent.length;
    pageContent = pageContent.filter(s => s.id !== sectionId);
    console.log(`Removed from array. Was ${originalLength}, now ${pageContent.length}`);
    
    if (pageContent.length === originalLength) {
        console.error('Section not found in array! ID:', sectionId);
        alert('Error: Section not found!');
        return;
    }
    
    // 3. Remove from DOM immediately
    const sectionElement = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (sectionElement) {
        sectionElement.remove();
        console.log('Removed from DOM');
    }
    
    // 4. Clear active section if needed
    if (activeSectionId === sectionId) {
        activeSectionId = null;
        const settingsPanel = document.getElementById('section-settings');
        if (settingsPanel) {
            settingsPanel.innerHTML = `
                <div class="text-gray-500 text-center py-8">
                    Select a section to edit its settings
                </div>
            `;
        }
    }
    
    // 5. Save to server
    console.log('Saving to server...');
    savePage();
    
    // 6. Show success message
    setTimeout(() => {
        alert('Section deleted successfully!');
    }, 500);
}

// Make sure function is available globally
window.deleteSection = deleteSection;

// ==================== RENDER FUNCTIONS ====================
function renderSections() {
    const container = document.getElementById('sections-container');
    if (!container) return;
    
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
    
    // Clear existing CKEditor instances
    Object.keys(ckeditorInstances).forEach(key => {
        ckeditorInstances[key].destroy();
        delete ckeditorInstances[key];
    });
    
    // Create a fresh template for each section
    pageContent.forEach((section) => {
        // Create section HTML directly with REAL IDs
        const sectionHtml = createSectionHTML(section);
        container.innerHTML += sectionHtml;
        
        // Initialize CKEditor for content sections
        if (section.type === 'content') {
            setTimeout(() => {
                const textareaId = 'editor-' + section.id;
                let textarea = document.getElementById(textareaId);
                
                if (!textarea) {
                    textarea = document.createElement('textarea');
                    textarea.id = textareaId;
                    textarea.style.display = 'none';
                    const sectionElement = container.lastElementChild;
                    const contentDiv = sectionElement.querySelector('.p-6');
                    if (contentDiv) {
                        contentDiv.appendChild(textarea);
                    }
                }
                
                textarea.value = section.content.html || '';
                initCKEditor(textareaId, section.content.html || '');
            }, 100);
        }
    });
}

function createSectionHTML(section) {
    let contentHtml = '';
    
    if (section.type === 'hero') {
        contentHtml = `
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" 
                           value="${section.content.title || ''}"
                           oninput="updateSectionContent('${section.id}', 'title', this.value)"
                           class="w-full px-3 py-2 border border-gray-300 rounded"
                           placeholder="Hero Title">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <textarea oninput="updateSectionContent('${section.id}', 'subtitle', this.value)"
                              class="w-full px-3 py-2 border border-gray-300 rounded"
                              rows="2"
                              placeholder="Hero subtitle text">${section.content.subtitle || ''}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                        <input type="text" 
                               value="${section.content.button_text || ''}"
                               oninput="updateSectionContent('${section.id}', 'button_text', this.value)"
                               class="w-full px-3 py-2 border border-gray-300 rounded"
                               placeholder="Learn More">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Link</label>
                        <input type="text" 
                               value="${section.content.button_link || ''}"
                               oninput="updateSectionContent('${section.id}', 'button_link', this.value)"
                               class="w-full px-3 py-2 border border-gray-300 rounded"
                               placeholder="/about">
                    </div>
                </div>
            </div>
        `;
    } else if (section.type === 'content') {
        contentHtml = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <div class="ckeditor-container">
                    <!-- CKEditor will be initialized -->
                </div>
                <p class="text-sm text-gray-500 mt-3">
                    Rich text editor. Changes auto-save.
                </p>
            </div>
        `;
    } else {
        contentHtml = `
            <div class="text-gray-500">
                ${section.type} section
            </div>
        `;
    }
    
    return `
        <div class="section-editor bg-white rounded-lg shadow border border-gray-200" 
             data-section-id="${section.id}">
            <!-- Section Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <div class="flex items-center">
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-medium mr-3">
                        ${section.type.charAt(0).toUpperCase() + section.type.slice(1)}
                    </div>
                    <div class="text-sm text-gray-500">Drag to reorder</div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="setActiveSection('${section.id}')"
                            class="text-gray-600 hover:text-gray-900 p-1">
                        ⚙️ Settings
                    </button>
                    <button onclick="deleteSection('${section.id}')"
                            class="text-red-600 hover:text-red-900 p-1">
                        🗑️ Delete
                    </button>
                </div>
            </div>
            
            <!-- Section Content -->
            <div class="p-6">
                ${contentHtml}
            </div>
        </div>
    `;
}

// ==================== UTILITY FUNCTIONS ====================
function setActiveSection(sectionId) {
    activeSectionId = sectionId;
    const section = pageContent.find(s => s.id === sectionId);
    
    if (!section) return;
    
    const settingsPanel = document.getElementById('section-settings');
    if (!settingsPanel) return;
    
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

function updateSectionSetting(sectionId, key, value) {
    const section = pageContent.find(s => s.id === sectionId);
    if (section) {
        if (!section.settings) section.settings = {};
        section.settings[key] = value;
        savePage();
    }
}

function updateSectionContent(sectionId, field, value) {
    const section = pageContent.find(s => s.id === sectionId);
    if (section) {
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

// ==================== SAVE FUNCTION ====================
function savePage() {
    const saveButton = document.getElementById('save-button');
    const saveIcon = document.getElementById('save-icon');
    const saveText = document.getElementById('save-text');
    
    // Show saving state
    if (saveButton && saveIcon && saveText) {
        saveButton.disabled = true;
        saveIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
        saveText.textContent = 'Saving...';
    }
    
    // Prepare data
    const data = {
        content: { sections: pageContent },
        seo: {
            title: document.getElementById('seo-title')?.value || '{{ $page->title }}',
            description: document.getElementById('seo-description')?.value || ''
        }
    };
    
    console.log('Saving page with', pageContent.length, 'sections');
    
    // Send to server
    fetch('{{ route("sites.pages.update", [$site, $page]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-HTTP-Method-Override': 'PUT',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Save response status:', response.status);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Server error:', text);
                throw new Error(`Save failed: ${response.status}`);
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('✅ Save successful:', data);
        
        if (saveButton && saveIcon && saveText) {
            saveIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
            saveText.textContent = 'Saved!';
            
            setTimeout(() => {
                saveText.textContent = 'Save';
                saveButton.disabled = false;
            }, 2000);
        }
    })
    .catch(error => {
        console.error('❌ Save error:', error);
        
        if (saveButton && saveIcon && saveText) {
            saveIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            saveText.textContent = 'Error';
            
            setTimeout(() => {
                saveText.textContent = 'Save';
                saveButton.disabled = false;
            }, 2000);
        }
        
        alert('Save failed: ' + error.message);
    });
}

// ==================== DEBUG FUNCTIONS ====================
function debugShowSections() {
    console.log('=== ALL SECTIONS ===');
    pageContent.forEach((s, i) => {
        console.log(`${i}: "${s.id}" (${s.type})`);
    });
    alert(`Total sections: ${pageContent.length}\nCheck console for details.`);
}

function deleteFirstSection() {
    if (pageContent.length > 0) {
        const firstId = pageContent[0].id;
        console.log('Testing delete of first section:', firstId);
        deleteSection(firstId);
    } else {
        alert('No sections to delete');
    }
}

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Editor loaded. Sections:', pageContent.length);
    renderSections();
    
    // Setup save button
    const saveButton = document.getElementById('save-button');
    if (saveButton) {
        saveButton.addEventListener('click', savePage);
    }
    
    // Auto-save on input
    let saveTimeout;
    document.addEventListener('input', function(e) {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(savePage, 2000);
    });
    
    // Load templates
    loadTemplates();
});

// ==================== TEMPLATE FUNCTIONS ====================
function loadTemplates() {
    fetch('{{ route("templates.index") }}?ajax=1')
        .then(response => response.text())
        .then(html => {
            const list = document.getElementById('template-list');
            if (list) list.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading templates:', error);
            const list = document.getElementById('template-list');
            if (list) {
                list.innerHTML = `
                    <div class="text-center py-4 text-red-600">
                        Failed to load templates
                    </div>
                `;
            }
        });
}

function showSaveTemplateModal() {
    // Modal code (simplified)
    alert('Save as template feature - to be implemented');
}

function showNotification(message, type = 'info') {
    // Notification code (simplified)
    console.log(`${type.toUpperCase()}: ${message}`);
}
</script>
@endsection