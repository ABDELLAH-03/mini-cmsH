@php
    $settings = $settings ?? [];
    $content = $content ?? [];
    $background = $settings['background'] ?? '#f8fafc';
    $textAlign = $settings['text_align'] ?? 'center';
@endphp

<div class="hero-section py-16" style="background-color: {{ $background }}; text-align: {{ $textAlign }}">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            {{ $content['title'] ?? 'Hero Title' }}
        </h1>
        <p class="text-xl text-gray-600 mb-8">
            {{ $content['subtitle'] ?? 'Hero subtitle text goes here' }}
        </p>
        @if(isset($content['button_text']))
        <a href="{{ $content['button_link'] ?? '#' }}" 
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            {{ $content['button_text'] }}
        </a>
        @endif
    </div>
</div>