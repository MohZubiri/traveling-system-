@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center mb-5">
        <div class="col-lg-6">
            <h1 class="mb-3">{{ __('messages.destinations_title') }}</h1>
            <p class="text-muted">{{ __('messages.destinations_description') }}</p>
        </div>
    </div>

    <div class="row g-4">
        @foreach($destinations as $destination)
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <img src="{{ $destination->image_url }}" class="card-img-top" alt="{{ $destination->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $destination->name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($destination->description, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-primary fw-bold">{{ __('messages.from') }} {{ $destination->price }}</span>
                            <div class="text-warning">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < floor($destination->rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i < $destination->rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <small class="text-muted">({{ $destination->total_reviews }})</small>
                            </div>
                        </div>
                        <a href="{{ route('destinations.show', $destination) }}" class="btn btn-outline-primary">
                            {{ __('messages.view_details') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $destinations->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
