<section class="section contact-section">
    <div class="container">
        @if(isset($section['content']['title']))
            <h2 class="text-3xl font-bold text-center mb-8">{{ $section['content']['title'] }}</h2>
        @endif
        
        <div class="contact-content">
            @if(isset($section['content']['email']))
                <div class="contact-item">
                    <strong>Email:</strong> 
                    <a href="mailto:{{ $section['content']['email'] }}">
                        {{ $section['content']['email'] }}
                    </a>
                </div>
            @endif
            
            @if(isset($section['content']['phone']))
                <div class="contact-item">
                    <strong>Phone:</strong> {{ $section['content']['phone'] }}
                </div>
            @endif
            
            @if(isset($section['content']['address']))
                <div class="contact-item">
                    <strong>Address:</strong> {{ $section['content']['address'] }}
                </div>
            @endif
        </div>
    </div>
</section>