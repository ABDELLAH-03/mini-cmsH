@php
    $indent = $depth * 24;
@endphp

<div class="page-item hover:bg-gray-50" data-page-id="{{ $page->id }}">
    <div class="flex items-center justify-between p-4" style="padding-left: {{ $indent + 16 }}px;">
        <div class="flex items-center">
            <!-- Drag Handle -->
            <div class="text-gray-400 mr-3 cursor-move">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                </svg>
            </div>
            
            <!-- Page Icon -->
            <div class="mr-3">
                @if($page->is_homepage)
                    <span class="text-yellow-500" title="Homepage">🏠</span>
                @else
                    <span class="text-gray-400">📄</span>
                @endif
            </div>
            
            <!-- Page Info -->
            <div>
                <div class="flex items-center">
                    <a href="{{ route('sites.pages.edit', [$site, $page]) }}" 
                       class="font-medium text-gray-900 hover:text-blue-600">
                        {{ $page->title }}
                    </a>
                    @if($page->is_homepage)
                        <span class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Home</span>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    /{{ $page->slug }}
                    @if($page->published_at)
                        • Published {{ $page->published_at->diffForHumans() }}
                    @else
                        • Draft
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center space-x-3">
            <!-- Set as Homepage -->
            @if(!$page->is_homepage)
                <form action="{{ route('sites.pages.set-homepage', [$site, $page]) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="text-sm text-gray-600 hover:text-gray-900"
                            title="Set as homepage">
                        Set Home
                    </button>
                </form>
            @endif
            
            <!-- Edit -->
            <a href="{{ route('sites.pages.edit', [$site, $page]) }}" 
               class="text-blue-600 hover:text-blue-800">
                Edit
            </a>
            
            <!-- Delete -->
            @if(!$page->is_homepage)
                <form action="{{ route('sites.pages.destroy', [$site, $page]) }}" method="POST" class="inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="text-red-600 hover:text-red-800"
                            onclick="return confirm('Delete this page?')">
                        Delete
                    </button>
                </form>
            @endif
        </div>
    </div>
    
    <!-- Child Pages -->
    @if($page->children->count() > 0)
        @foreach($page->children as $child)
            @include('pages.partials.page-item', ['page' => $child, 'depth' => $depth + 1])
        @endforeach
    @endif
</div>