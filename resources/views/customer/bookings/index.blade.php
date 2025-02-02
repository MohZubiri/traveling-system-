@extends('layouts.customer')

@section('title', 'حجوزاتي')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">حجوزاتي</h4>
        <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> حجز جديد
        </a>
    </div>

    <!-- Bookings List -->
    <div class="row">
        @forelse($bookings as $booking)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-{{ $booking->service_type === 'bus' ? 'bus' : 'car' }} me-1"></i>
                                {{ $booking->service_type === 'bus' ? 'حجز حافلة' : 'حجز سيارة' }}
                            </h6>
                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ $booking->status === 'confirmed' ? 'مؤكد' : ($booking->status === 'pending' ? 'معلق' : 'ملغي') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                <span>{{ $booking->date->format('Y-m-d') }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock text-muted me-2"></i>
                                <span>{{ date('H:i', strtotime($booking->time)) }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                <span>{{ $booking->location }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ticket-alt text-muted me-2"></i>
                                <span>{{ $booking->tickets->count() }} تذاكر</span>
                            </div>
                        </div>

                        <div class="alert alert-light mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>التكلفة الإجمالية</span>
                                <strong>{{ number_format($booking->cost, 2) }} ريال</strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('customer.bookings.show', $booking) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i> عرض التفاصيل
                            </a>
                            <div>
                                @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                    @if($booking->canModify())
                                        <a href="{{ route('customer.bookings.edit', $booking) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-edit me-1"></i> تعديل
                                        </a>
                                    @endif
                                    @if($booking->canCancel())
                                        <form action="{{ route('customer.bookings.cancel', $booking) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('هل أنت متأكد من إلغاء الحجز؟')">
                                                <i class="fas fa-times me-1"></i> إلغاء
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <img src="https://via.placeholder.com/120" alt="No Bookings" class="mb-3">
                    <h5>لا توجد حجوزات</h5>
                    <p class="text-muted mb-0">قم بإنشاء حجز جديد للبدء</p>
                    <a href="{{ route('customer.bookings.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-1"></i> حجز جديد
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
