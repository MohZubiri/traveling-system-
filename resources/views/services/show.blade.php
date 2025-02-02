@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-4xl mx-auto">
        @if($service->image)
            <img src="{{ asset($service->image) }}" alt="{{ $service->title }}" class="w-full h-64 object-cover rounded-lg shadow-lg mb-8">
        @endif

        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $service->title }}</h1>
        
        <div class="prose prose-lg max-w-none">
            {!! $service->description !!}
        </div>

        <div class="mt-8">
            <a href="{{ route('services.index') }}" class="inline-block bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition-colors">
                العودة إلى الخدمات
            </a>
        </div>
    </div>
</div>
@endsection
