@extends('layouts.customer')

@section('title', 'تعديل الحجز')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">تعديل الحجز</h5>
                </div>
                
                <form action="{{ route('customer.bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <!-- Service Type (Read Only) -->
                        <div class="mb-4">
                            <label class="form-label">نوع الخدمة</label>
                            <div class="alert alert-light mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-{{ $booking->service_type === 'bus' ? 'bus' : 'car' }} text-primary me-2"></i>
                                    <span>{{ $booking->service_type === 'bus' ? 'حافلة' : 'سيارة' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">التاريخ <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" required
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       value="{{ old('date', $booking->date->format('Y-m-d')) }}">
                                @error('date')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الوقت <span class="text-danger">*</span></label>
                                <input type="time" name="time" class="form-control" required
                                       value="{{ old('time', date('H:i', strtotime($booking->time))) }}">
                                @error('time')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label class="form-label">موقع الانطلاق <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" required
                                   value="{{ old('location', $booking->location) }}">
                            @error('location')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label">ملاحظات إضافية</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                    placeholder="أي معلومات إضافية ترغب في إضافتها...">{{ old('notes', $booking->notes) }}</textarea>
                        </div>

                        <!-- Cost Preview -->
                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading mb-2">تفاصيل التكلفة</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>عدد التذاكر:</span>
                                <strong>{{ $booking->tickets->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span>إجمالي التكلفة:</span>
                                <strong>{{ number_format($booking->cost, 2) }} ريال</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> حفظ التغييرات
                        </button>
                        <a href="{{ route('customer.bookings.show', $booking) }}" class="btn btn-light">
                            <i class="fas fa-times me-1"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
