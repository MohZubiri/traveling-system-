@extends('layouts.customer')

@section('title', 'حجز جديد')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">حجز جديد</h5>
                </div>
                
                <form action="{{ route('customer.bookings.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <!-- Service Type -->
                        <div class="mb-4">
                            <label class="form-label">نوع الخدمة <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check card">
                                        <div class="card-body">
                                            <input class="form-check-input" type="radio" name="service_type" 
                                                   id="bus" value="bus" required>
                                            <label class="form-check-label w-100" for="bus">
                                                <i class="fas fa-bus fa-2x mb-2 text-primary"></i>
                                                <h6 class="mb-1">حافلة</h6>
                                                <small class="text-muted d-block mb-2">مناسبة للمجموعات الكبيرة</small>
                                                <span class="badge bg-primary">50 ريال / شخص</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check card">
                                        <div class="card-body">
                                            <input class="form-check-input" type="radio" name="service_type" 
                                                   id="car" value="car" required>
                                            <label class="form-check-label w-100" for="car">
                                                <i class="fas fa-car fa-2x mb-2 text-primary"></i>
                                                <h6 class="mb-1">سيارة</h6>
                                                <small class="text-muted d-block mb-2">مناسبة للعائلات الصغيرة</small>
                                                <span class="badge bg-primary">150 ريال / شخص</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('service_type')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date & Time -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">التاريخ <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" required
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       value="{{ old('date') }}">
                                @error('date')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الوقت <span class="text-danger">*</span></label>
                                <input type="time" name="time" class="form-control" required
                                       value="{{ old('time') }}">
                                @error('time')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label class="form-label">موقع الانطلاق <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" required
                                   placeholder="أدخل عنوان موقع الانطلاق..."
                                   value="{{ old('location') }}">
                            @error('location')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Number of Passengers -->
                        <div class="mb-4">
                            <label class="form-label">عدد الركاب <span class="text-danger">*</span></label>
                            <select name="passengers" class="form-select" required>
                                <option value="">اختر عدد الركاب</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ old('passengers') == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i === 1 ? 'راكب' : 'ركاب' }}
                                    </option>
                                @endfor
                            </select>
                            @error('passengers')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label">ملاحظات إضافية</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                    placeholder="أي معلومات إضافية ترغب في إضافتها...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Terms -->
                        <div class="alert alert-light mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    أوافق على <a href="#" class="text-decoration-none">الشروط والأحكام</a>
                                </label>
                            </div>
                        </div>

                        <!-- Cost Preview -->
                        <div class="alert alert-info mb-0" id="costPreview" style="display: none;">
                            <h6 class="alert-heading mb-2">تفاصيل التكلفة</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>التكلفة للشخص الواحد:</span>
                                <strong id="costPerPerson">0 ريال</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span>إجمالي التكلفة:</span>
                                <strong id="totalCost">0 ريال</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-1"></i> تأكيد الحجز
                        </button>
                        <a href="{{ route('customer.bookings.index') }}" class="btn btn-light">
                            <i class="fas fa-times me-1"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    function updateCostPreview() {
        const serviceType = $('input[name="service_type"]:checked').val();
        const passengers = $('select[name="passengers"]').val();
        
        if (serviceType && passengers) {
            const costPerPerson = serviceType === 'bus' ? 50 : 150;
            const totalCost = costPerPerson * passengers;
            
            $('#costPerPerson').text(costPerPerson + ' ريال');
            $('#totalCost').text(totalCost + ' ريال');
            $('#costPreview').show();
        } else {
            $('#costPreview').hide();
        }
    }

    $('input[name="service_type"]').change(updateCostPreview);
    $('select[name="passengers"]').change(updateCostPreview);
});
</script>
@endpush
@endsection
