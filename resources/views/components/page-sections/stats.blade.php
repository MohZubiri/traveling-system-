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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                $stats = json_decode($section->content, true) ?? [];
            @endphp

            @foreach($stats as $stat)
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2 text-primary">
                        <span class="counter" data-target="{{ $stat['value'] ?? 0 }}">0</span>
                        {{ $stat['suffix'] ?? '' }}
                    </div>
                    <p class="text-lg"
                       style="color: {{ $section->text_color ?? '#4a5568' }}">
                        {{ $stat['label'] ?? '' }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    const updateCount = (counter, target) => {
        const count = +counter.innerText;
        const increment = target / speed;

        if (count < target) {
            counter.innerText = Math.ceil(count + increment);
            setTimeout(() => updateCount(counter, target), 1);
        } else {
            counter.innerText = target;
        }
    };

    const observerCallback = (entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = +counter.getAttribute('data-target');
                updateCount(counter, target);
            }
        });
    };

    const observer = new IntersectionObserver(observerCallback, {
        threshold: 0.5
    });

    counters.forEach(counter => observer.observe(counter));
});
</script>
@endpush
