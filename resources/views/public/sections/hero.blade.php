<section class="section hero-section" style="{{ isset($section['settings']['background']) ? 'background: ' . $section['settings']['background'] : '' }}">
    <div class="container">
        <h1 class="hero-title">{{ $section['content']['title'] ?? 'Welcome' }}</h1>
        <p class="hero-subtitle">{{ $section['content']['subtitle'] ?? '' }}</p>
        @if(isset($section['content']['button_text']))
            <a href="{{ $section['content']['button_link'] ?? '#' }}" class="btn">
                {{ $section['content']['button_text'] }}
            </a>
        @endif
    </div>
</section>