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

        <div class="max-w-3xl mx-auto">
            @php
                $faqs = json_decode($section->content, true) ?? [];
            @endphp

            <div class="space-y-4">
                @foreach($faqs as $index => $faq)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button class="w-full px-6 py-4 text-right bg-white hover:bg-gray-50 focus:outline-none flex items-center justify-between"
                                onclick="toggleFaq({{ $index }})"
                                style="color: {{ $section->text_color ?? '#1a1a1a' }}">
                            <span class="font-medium">{{ $faq['question'] ?? '' }}</span>
                            <svg class="w-5 h-5 transform transition-transform duration-200 faq-icon-{{ $index }}"
                                 fill="none" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round" 
                                      stroke-width="2" 
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div class="px-6 py-4 bg-white hidden faq-content-{{ $index }}"
                             style="color: {{ $section->text_color ?? '#4a5568' }}">
                            {!! $faq['answer'] ?? '' !!}
                        </div>
                    </div>
                @endforeach
            </div>
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

@push('scripts')
<script>
function toggleFaq(index) {
    const content = document.querySelector(`.faq-content-${index}`);
    const icon = document.querySelector(`.faq-icon-${index}`);
    
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
@endpush
