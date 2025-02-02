<section class="py-16" style="background-color: {{ $section->background_color ?? '#ffffff' }}">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            @if($section->title)
                <h2 class="text-4xl font-bold text-center mb-6"
                    style="color: {{ $section->text_color ?? '#1a1a1a' }}">
                    {{ $section->title }}
                </h2>
            @endif

            @if($section->subtitle)
                <p class="text-xl text-center mb-8 opacity-80"
                   style="color: {{ $section->text_color ?? '#4a5568' }}">
                    {{ $section->subtitle }}
                </p>
            @endif

            @if($section->content)
                <div class="prose prose-lg mx-auto"
                     style="color: {{ $section->text_color ?? '#2d3748' }}">
                    {!! $section->content !!}
                </div>
            @endif

            @if($section->button_text && $section->button_url)
                <div class="text-center mt-8">
                    <a href="{{ $section->button_url }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark transition-colors duration-200">
                        {{ $section->button_text }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
