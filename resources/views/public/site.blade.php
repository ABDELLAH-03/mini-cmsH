<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->seo['title'] ?? $page->title }} - {{ $site->name }}</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $page->seo['description'] ?? '' }}">
    <meta name="keywords" content="{{ $page->seo['keywords'] ?? '' }}">
    
    <!-- Theme CSS -->
    <style>
        {{ $theme }}
        
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-family);
            line-height: 1.6;
            color: #333;
        }
        
        .container {
            width: 100%;
            max-width: var(--container-width);
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        /* Navigation */
        .site-nav {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .site-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .nav-link {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: var(--primary-color);
        }
        
        /* Sections */
        .section {
            padding: 4rem 0;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 6rem 1rem;
        }
        
        .hero-title {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: transform 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .content-section {
            background: white;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .feature-card {
            padding: 2rem;
            background: #f8fafc;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }
        
        /* Footer */
        .site-footer {
            background: #1a202c;
            color: white;
            padding: 3rem 0;
            text-align: center;
        }
    </style>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="site-nav">
        <div class="container nav-container">
            <a href="/" class="site-logo">{{ $site->name }}</a>
            <ul class="nav-menu">
                @foreach($navigation as $item)
                    <li>
                        <a href="{{ $item['is_homepage'] ? '/' : '/' . $item['slug'] }}" 
                           class="nav-link">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @if($page->content && isset($page->content['sections']))
            @foreach($page->content['sections'] as $section)
                @include('public.sections.' . ($section['type'] ?? 'content'), [
                    'section' => $section,
                    'site' => $site
                ])
            @endforeach
        @else
            <div class="section content-section">
                <div class="container">
                    <h1>{{ $page->title }}</h1>
                    <p>Page content will appear here.</p>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ $site->name }}. All rights reserved.</p>
            <p>Built with Mini CMS</p>
        </div>
    </footer>

    <!-- Simple Analytics Script -->
    <script>
        // Simple page view tracking
        if (navigator.sendBeacon) {
            navigator.sendBeacon('/api/page-view', JSON.stringify({
                page_id: {{ $page->id }},
                site_id: {{ $site->id }}
            }));
        }
    </script>
</body>
</html>