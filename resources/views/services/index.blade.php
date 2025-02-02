@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">خدماتنا</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($services as $service)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                @if($service->image)
                    <img src="{{ asset($service->image) }}" alt="{{ $service->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $service->title }}</h2>
                    <p class="text-gray-600 mb-4">{{ Str::limit($service->description, 150) }}</p>
                    <a href="{{ route('services.show', $service) }}" class="inline-block bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark transition-colors">
                        المزيد من التفاصيل
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $services->links() }}
    </div>
</div>
@endsection
