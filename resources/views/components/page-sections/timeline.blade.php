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

        <div class="relative">
            <!-- Timeline Line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-0.5 bg-gray-200"></div>

            <div class="relative">
                @php
                    $events = json_decode($section->content, true) ?? [];
                @endphp

                @foreach($events as $index => $event)
                    <div class="mb-12 relative">
                        <!-- Timeline Point -->
                        <div class="absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 bg-primary rounded-full"></div>

                        <!-- Timeline Content -->
                        <div class="flex items-center justify-between">
                            <div class="{{ $index % 2 == 0 ? 'w-1/2 pr-8 text-right' : 'w-1/2 ml-auto pl-8' }}">
                                @if(!empty($event['date']))
                                    <div class="text-lg font-semibold text-primary mb-2">
                                        {{ $event['date'] }}
                                    </div>
                                @endif

                                <h3 class="text-xl font-bold mb-2"
                                    style="color: {{ $section->text_color ?? '#1a1a1a' }}">
                                    {{ $event['title'] ?? '' }}
                                </h3>

                                <p class="text-gray-600">
                                    {{ $event['description'] ?? '' }}
                                </p>

                                @if(!empty($event['image']))
                                    <img src="{{ Storage::url($event['image']) }}" 
                                         alt="{{ $event['title'] ?? '' }}"
                                         class="mt-4 rounded-lg shadow-md max-w-xs mx-auto">
                                @endif
                            </div>
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

@push('styles')
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.timeline-item {
    opacity: 0;
    animation: fadeInUp 0.6s ease-out forwards;
}

.timeline-item:nth-child(even) {
    animation-delay: 0.3s;
}
</style>
@endpush
