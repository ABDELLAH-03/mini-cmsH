<section class="section">
    <div class="container">
        @if(isset($section['content']['title']))
            <h2 class="text-3xl font-bold text-center mb-8">{{ $section['content']['title'] }}</h2>
        @endif
        
        <div class="features-grid">
            @foreach(($section['content']['items'] ?? []) as $item)
                <div class="feature-card">
                    <h3 class="text-xl font-semibold mb-2">{{ $item['title'] ?? 'Feature' }}</h3>
                    <p class="text-gray-600">{{ $item['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>