<section class="py-16" style="background-color: {{ $section->background_color ?? '#1a56db' }}">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            @if($section->title)
                <h2 class="text-4xl font-bold mb-6"
                    style="color: {{ $section->text_color ?? '#ffffff' }}">
                    {{ $section->title }}
                </h2>
            @endif

            @if($section->subtitle)
                <p class="text-xl mb-8 opacity-90"
                   style="color: {{ $section->text_color ?? '#ffffff' }}">
                    {{ $section->subtitle }}
                </p>
            @endif

            @if($section->content)
                <div class="prose prose-lg mx-auto mb-8"
                     style="color: {{ $section->text_color ?? '#ffffff' }}">
                    {!! $section->content !!}
                </div>
            @endif

            @if($section->button_text && $section->button_url)
                <a href="{{ $section->button_url }}" 
                   class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-medium rounded-md text-primary bg-white hover:bg-gray-100 transition-colors duration-200">
                    {{ $section->button_text }}
                </a>
            @endif
        </div>
    </div>
</section>
