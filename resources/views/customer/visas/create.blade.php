@extends('layouts.customer')

@section('title', 'تقديم طلب تأشيرة جديد')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">تقديم طلب تأشيرة جديد</h5>
                </div>
                
                <form action="{{ route('customer.visas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <!-- Visa Type -->
                        <div class="mb-4">
                            <label class="form-label">نوع التأشيرة <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check card">
                                        <div class="card-body">
                                            <input class="form-check-input" type="radio" name="type" 
                                                   id="hajj" value="hajj" required>
                                            <label class="form-check-label w-100" for="hajj">
                                                <i class="fas fa-kaaba fa-2x mb-2 text-primary"></i>
                                                <h6 class="mb-1">تأشيرة حج</h6>
                                                <small class="text-muted">للراغبين في أداء فريضة الحج</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check card">
                                        <div class="card-body">
                                            <input class="form-check-input" type="radio" name="type" 
                                                   id="umrah" value="umrah" required>
                                            <label class="form-check-label w-100" for="umrah">
                                                <i class="fas fa-mosque fa-2x mb-2 text-primary"></i>
                                                <h6 class="mb-1">تأشيرة عمرة</h6>
                                                <small class="text-muted">للراغبين في أداء العمرة</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check card">
                                        <div class="card-body">
                                            <input class="form-check-input" type="radio" name="type" 
                                                   id="work" value="work" required>
                                            <label class="form-check-label w-100" for="work">
                                                <i class="fas fa-briefcase fa-2x mb-2 text-primary"></i>
                                                <h6 class="mb-1">تأشيرة عمل</h6>
                                                <small class="text-muted">للراغبين في العمل</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('type')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Required Documents -->
                        <div class="mb-4">
                            <h6 class="mb-3">المستندات المطلوبة</h6>
                            
                            <div class="document-uploads">
                                <!-- Passport Copy -->
                                <div class="mb-3">
                                    <label class="form-label">صورة جواز السفر <span class="text-danger">*</span></label>
                                    <input type="hidden" name="document_names[]" value="passport">
                                    <input type="file" name="documents[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">يجب أن يكون الجواز ساري المفعول لمدة لا تقل عن 6 أشهر</small>
                                </div>

                                <!-- Personal Photo -->
                                <div class="mb-3">
                                    <label class="form-label">الصورة الشخصية <span class="text-danger">*</span></label>
                                    <input type="hidden" name="document_names[]" value="photo">
                                    <input type="file" name="documents[]" class="form-control" accept=".jpg,.jpeg,.png" required>
                                    <small class="text-muted">صورة شخصية حديثة بخلفية بيضاء</small>
                                </div>

                                <!-- Additional Documents based on visa type -->
                                <div id="additionalDocs"></div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label">ملاحظات إضافية</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                    placeholder="أي معلومات إضافية ترغب في إضافتها..."></textarea>
                        </div>

                        <!-- Terms -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                أوافق على جميع الشروط والأحكام
                            </label>
                        </div>
                    </div>

                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> تقديم الطلب
                        </button>
                        <a href="{{ route('customer.visas.index') }}" class="btn btn-light">
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
    // Handle additional documents based on visa type
    $('input[name="type"]').change(function() {
        const type = $(this).val();
        const additionalDocs = $('#additionalDocs');
        additionalDocs.empty();

        if (type === 'work') {
            additionalDocs.append(`
                <div class="mb-3">
                    <label class="form-label">السيرة الذاتية <span class="text-danger">*</span></label>
                    <input type="hidden" name="document_names[]" value="cv">
                    <input type="file" name="documents[]" class="form-control" accept=".pdf" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">شهادات الخبرة <span class="text-danger">*</span></label>
                    <input type="hidden" name="document_names[]" value="experience">
                    <input type="file" name="documents[]" class="form-control" accept=".pdf" required>
                </div>
            `);
        } else if (type === 'hajj' || type === 'umrah') {
            additionalDocs.append(`
                <div class="mb-3">
                    <label class="form-label">شهادة التطعيم <span class="text-danger">*</span></label>
                    <input type="hidden" name="document_names[]" value="vaccination">
                    <input type="file" name="documents[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
            `);
        }
    });
});
</script>
@endpush
@endsection
