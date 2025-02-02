<section class="py-16" style="background-color: {{ $section->background_color ?? '#ffffff' }}">
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $images = json_decode($section->content, true) ?? [];
            @endphp

            @foreach($images as $image)
                <div class="group relative overflow-hidden rounded-lg cursor-pointer"
                     onclick="openGalleryModal('{{ $image['url'] }}', '{{ $image['caption'] ?? '' }}')">
                    <img src="{{ Storage::url($image['url']) }}" 
                         alt="{{ $image['caption'] ?? '' }}"
                         class="w-full h-64 object-cover transform transition-transform duration-300 group-hover:scale-110">
                    
                    @if(!empty($image['caption']))
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                            <p class="text-white p-4">{{ $image['caption'] }}</p>
                        </div>
                    @endif
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

@push('scripts')
<script>
function openGalleryModal(imageUrl, caption) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75';
    modal.onclick = e => {
        if (e.target === modal) modal.remove();
    };

    const content = document.createElement('div');
    content.className = 'max-w-4xl w-full bg-white rounded-lg overflow-hidden';
    
    const img = document.createElement('img');
    img.src = imageUrl;
    img.className = 'w-full h-auto';
    
    content.appendChild(img);
    
    if (caption) {
        const captionDiv = document.createElement('div');
        captionDiv.className = 'p-4 text-center text-gray-700';
        captionDiv.textContent = caption;
        content.appendChild(captionDiv);
    }
    
    modal.appendChild(content);
    document.body.appendChild(modal);
}
</script>
@endpush
