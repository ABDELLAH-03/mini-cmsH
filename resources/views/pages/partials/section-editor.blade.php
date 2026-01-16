<div class="section-editor bg-white rounded-lg shadow border border-gray-200" data-section-id="{{ $section['id'] }}">
    <!-- Section Header -->
    <div class="flex items-center justify-between p-4 border-b">
        <div class="flex items-center">
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm font-medium mr-3 section-type">
                {{ ucfirst($section['type']) }}
            </div>
            <div class="text-sm text-gray-500">Drag to reorder</div>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="setActiveSection('{{ $section['id'] }}')"
                    class="text-gray-600 hover:text-gray-900 p-1">
                ⚙️ Settings
            </button>
            <button onclick="deleteSection('{{ $section['id'] }}')"
                    class="text-red-600 hover:text-red-900 p-1">
                🗑️ Delete
            </button>
        </div>
    </div>
    
    <!-- Section Content -->
    <div class="p-6">
        @if($section['type'] === 'hero')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" 
                       value="{{ $section['content']['title'] ?? '' }}"
                       oninput="updateSectionContent('{{ $section['id'] }}', 'title', this.value)"
                       class="w-full px-3 py-2 border border-gray-300 rounded"
                       placeholder="Hero Title">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                <textarea oninput="updateSectionContent('{{ $section['id'] }}', 'subtitle', this.value)"
                          class="w-full px-3 py-2 border border-gray-300 rounded"
                          rows="2"
                          placeholder="Hero subtitle text">{{ $section['content']['subtitle'] ?? '' }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                    <input type="text" 
                           value="{{ $section['content']['button_text'] ?? '' }}"
                           oninput="updateSectionContent('{{ $section['id'] }}', 'button_text', this.value)"
                           class="w-full px-3 py-2 border border-gray-300 rounded"
                           placeholder="Learn More">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Button Link</label>
                    <input type="text" 
                           value="{{ $section['content']['button_link'] ?? '' }}"
                           oninput="updateSectionContent('{{ $section['id'] }}', 'button_link', this.value)"
                           class="w-full px-3 py-2 border border-gray-300 rounded"
                           placeholder="/about">
                </div>
            </div>
        </div>
        
        @elseif($section['type'] === 'content')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
            <textarea oninput="updateSectionContent('{{ $section['id'] }}', 'html', this.value)"
                      class="w-full px-3 py-2 border border-gray-300 rounded font-mono"
                      rows="6"
                      placeholder="Enter your content here...">{{ $section['content']['html'] ?? '' }}</textarea>
            <p class="text-sm text-gray-500 mt-1">You can use HTML tags for formatting.</p>
        </div>
        
        @elseif($section['type'] === 'features')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
            <div id="features-container-{{ $section['id'] }}">
                @foreach(($section['content']['items'] ?? []) as $index => $item)
                <div class="flex items-center space-x-2 mb-2">
                    <input type="text" 
                           value="{{ $item['title'] ?? '' }}"
                           oninput="updateSectionContent('{{ $section['id'] }}', 'items.{{ $index }}.title', this.value)"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded"
                           placeholder="Feature Title">
                    <input type="text" 
                           value="{{ $item['description'] ?? '' }}"
                           oninput="updateSectionContent('{{ $section['id'] }}', 'items.{{ $index }}.description', this.value)"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded"
                           placeholder="Feature Description">
                    <button onclick="removeFeature('{{ $section['id'] }}', {{ $index }})"
                            class="text-red-600 p-2">
                        ×
                    </button>
                </div>
                @endforeach
            </div>
            <button onclick="addFeature('{{ $section['id'] }}')"
                    class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                + Add Feature
            </button>
        </div>
        <script>
        function addFeature(sectionId) {
            const container = document.getElementById('features-container-' + sectionId);
            const index = container.children.length;
            
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2 mb-2';
            div.innerHTML = `
                <input type="text" 
                       oninput="updateSectionContent('${sectionId}', 'items.${index}.title', this.value)"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded"
                       placeholder="Feature Title">
                <input type="text" 
                       oninput="updateSectionContent('${sectionId}', 'items.${index}.description', this.value)"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded"
                       placeholder="Feature Description">
                <button onclick="this.parentElement.remove()"
                        class="text-red-600 p-2">
                    ×
                </button>
            `;
            container.appendChild(div);
        }
        
        function removeFeature(sectionId, index) {
            // Update the pageContent array
            const section = pageContent.find(s => s.id === sectionId);
            if (section && section.content.items) {
                section.content.items.splice(index, 1);
                savePage();
            }
        }
        </script>
        
        @else
        <div class="text-gray-500">
            Edit interface for {{ $section['type'] }} section will be added soon.
        </div>
        @endif
    </div>
</div>