@php
    $content = $content ?? [];
    $padding = $settings['padding'] ?? 'p-8';
@endphp

<div class="content-section {{ $padding }}">
    <div class="container mx-auto px-4">
        {!! $content['html'] ?? '<p>Add your content here</p>' !!}
    </div>
</div>