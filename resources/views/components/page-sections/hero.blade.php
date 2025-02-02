<section class="relative bg-primary h-screen flex items-center" 
        style="background-color: {{ $section->background_color ?? '#1a56db' }}">
    
    @if($section->image)
        <div class="absolute inset-0">
            <img src="{{ $section->image_url }}" alt="{{ $section->title }}" 
                 class="w-full h-full object-cover opacity-20">
        </div>
    @endif

    <div class="relative container mx-auto px-4 py-32">
        <div class="max-w-3xl mx-auto text-center">
            @if($section->title)
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                    {{ $section->title }}
                </h1>
            @endif

            @if($section->subtitle)
                <p class="text-xl md:text-2xl text-white opacity-90 mb-8">
                    {{ $section->subtitle }}
                </p>
            @endif

            @if($section->content)
                <div class="text-lg text-white opacity-80 mb-12">
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
