@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Destination Details -->
    <div class="row mb-5">
        <div class="col-lg-6">
            <div id="destinationCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded-4">
                    @foreach($destination->images as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ $image->image_url }}" class="d-block w-100" alt="{{ $destination->name }}">
                    </div>
                    @endforeach
                </div>
                @if($destination->images->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#destinationCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#destinationCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <h1 class="mb-3">{{ $destination->name }}</h1>
            <div class="d-flex align-items-center mb-3">
                <div class="text-warning me-2">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < floor($destination->rating))
                            <i class="fas fa-star"></i>
                        @elseif($i < $destination->rating)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-muted">({{ $destination->total_reviews }} {{ __('messages.reviews') }})</span>
            </div>
            <h3 class="text-primary mb-4">{{ __('messages.from') }} {{ $destination->price }}</h3>
            <p class="mb-4">{{ $destination->description }}</p>
            <div class="d-flex align-items-center mb-4">
                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                <span>{{ $destination->location }}</span>
            </div>
            <a href="{{ route('destinations.book', $destination) }}" class="btn btn-primary btn-lg">
                {{ __('messages.book_now') }}
            </a>
        </div>
    </div>

    <!-- Related Destinations -->
    @if($relatedDestinations->isNotEmpty())
    <div class="related-destinations">
        <h2 class="mb-4">{{ __('messages.related_destinations') }}</h2>
        <div class="row g-4">
            @foreach($relatedDestinations as $related)
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ $related->image_url }}" class="card-img-top" alt="{{ $related->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $related->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($related->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">{{ __('messages.from') }} {{ $related->price }}</span>
                            <a href="{{ route('destinations.show', $related) }}" class="btn btn-outline-primary">
                                {{ __('messages.view_details') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .carousel-item img {
        height: 400px;
        object-fit: cover;
    }
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
