<section class="py-16" style="background-color: {{ $section->background_color ?? '#f7fafc' }}">
    <div class="container mx-auto px-4">
        @if($section->title)
            <h2 class="text-4xl font-bold text-center mb-6"
                style="color: {{ $section->text_color ?? '#1a1a1a' }}">
                {{ $section->title }}
            </h2>
        @endif

        @if($section->subtitle)
            <p class="text-xl text-center mb-12 opacity-80"
               style="color: {{ $section->text_color ?? '#4a5568' }}">
                {{ $section->subtitle }}
            </p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $features = json_decode($section->content, true) ?? [];
            @endphp

            @foreach($features as $feature)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    @if(!empty($feature['icon']))
                        <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-4">
                            <i class="{{ $feature['icon'] }} text-2xl text-white"></i>
                        </div>
                    @endif

                    <h3 class="text-xl font-semibold mb-3"
                        style="color: {{ $section->text_color ?? '#1a1a1a' }}">
                        {{ $feature['title'] ?? '' }}
                    </h3>

                    <p class="text-gray-600">
                        {{ $feature['description'] ?? '' }}
                    </p>
                </div>
            @endforeach
        </div>

        @if($section->button_text && $section->button_url)
            <div class="text-center mt-12">
                <a href="{{ $section->button_url }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark transition-colors duration-200">
                    {{ $section->button_text }}
                </a>
            </div>
        @endif
    </div>
</section>
