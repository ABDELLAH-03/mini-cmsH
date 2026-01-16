@if($templates->isEmpty())
    <div class="text-center py-4 text-gray-500 text-sm">
        No templates yet. Create one from a page!
    </div>
@else
    <div class="space-y-3">
        @foreach($templates as $template)
        <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
            <div class="flex items-start justify-between">
                <div>
                    <div class="font-medium text-sm">{{ $template->name }}</div>
                    <div class="text-xs text-gray-500 mt-1">
                        <span class="inline-block px-2 py-1 rounded-full 
                            {{ $template->type === 'full_page' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($template->type) }}
                        </span>
                        @if($template->category !== 'general')
                            <span class="ml-1">{{ ucfirst($template->category) }}</span>
                        @endif
                    </div>
                </div>
                @if($template->visibility === 'system')
                    <span class="text-xs text-yellow-600" title="System template">⭐</span>
                @elseif($template->visibility === 'public')
                    <span class="text-xs text-green-600" title="Public template">🌍</span>
                @endif
            </div>
            
            <div class="mt-3">
    <button onclick="applyTemplate({{ $template->id }}, {{ $template->type === 'full_page' ? 'true' : 'false' }})"
            class="w-full text-sm px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
        {{ $template->type === 'full_page' ? 'Apply Full Page' : 'Add Section' }}
    </button>
</div>
        </div>
        @endforeach
    </div>
@endif