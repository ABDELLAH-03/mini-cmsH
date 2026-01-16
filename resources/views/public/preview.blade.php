<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $page->title }} - {{ $site->name }}</title>
    
    <style>
        .preview-banner {
            background: #4F46E5;
            color: white;
            padding: 0.5rem;
            text-align: center;
            font-size: 0.875rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10000;
        }
        
        .preview-banner a {
            color: white;
            text-decoration: underline;
            margin-left: 1rem;
        }
        
        body {
            padding-top: 2rem;
        }
    </style>
</head>
<body>
    <!-- Preview Banner -->
    <div class="preview-banner">
        🔄 Live Preview Mode - Editing: {{ $page->title }}
        <a href="{{ route('sites.pages.edit', [$site, $page]) }}">← Back to Editor</a>
    </div>
    
    <!-- Render the actual page -->
    @include('public.site', [
        'site' => $site,
        'page' => $page,
        'content' => $content,
        'theme' => $theme ?? ''
    ])
    
    <!-- Auto-refresh script for live preview -->
    <script>
        let lastUpdate = Date.now();
        
        // Check for updates every 2 seconds
        setInterval(() => {
            fetch(`/api/pages/{{ $page->id }}/last-modified`)
                .then(response => response.json())
                .then(data => {
                    if (data.last_modified > lastUpdate) {
                        console.log('Content updated, refreshing...');
                        location.reload();
                    }
                });
        }, 2000);
        
        // Listen for editor save messages (if using Broadcast)
        if (typeof Echo !== 'undefined') {
            Echo.private(`page.{{ $page->id }}`)
                .listen('PageUpdated', (e) => {
                    console.log('Page updated via WebSocket, refreshing...');
                    location.reload();
                });
        }
        // Listen for content updates from editor
window.addEventListener('message', function(event) {
    if (event.data.type === 'content-update') {
        console.log('Content update received at:', new Date().toLocaleTimeString());
        
        // Show updating indicator
        showUpdatingIndicator();
        
        // Reload the page to show new content
        setTimeout(() => {
            location.reload();
        }, 500);
    }
});

// Show updating indicator
function showUpdatingIndicator() {
    const indicator = document.createElement('div');
    indicator.id = 'updating-indicator';
    indicator.className = 'fixed bottom-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    indicator.innerHTML = '🔄 Updating preview...';
    
    document.body.appendChild(indicator);
    
    setTimeout(() => {
        if (indicator.parentElement) {
            indicator.remove();
        }
    }, 3000);
}

// Notify editor that preview is ready
if (window.opener) {
    window.addEventListener('load', function() {
        setTimeout(() => {
            window.opener.postMessage({ type: 'preview-ready' }, '*');
        }, 1000);
    });
}
    </script>
</body>
</html>