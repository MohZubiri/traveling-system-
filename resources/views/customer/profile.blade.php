@extends('layouts.customer')

@section('title', 'الملف الشخصي')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/150" alt="Profile Picture" class="rounded-circle mb-3" width="150">
                    <h4 class="mb-0">{{ $customer->name }}</h4>
                    <p class="text-muted">{{ $customer->nationality }}</p>
                    <div class="mt-3">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updatePhotoModal">
                            <i class="fas fa-camera me-1"></i> تغيير الصورة
                        </button>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col">
                            <div class="fw-bold">{{ $visaCount ?? 0 }}</div>
                            <small>التأشيرات</small>
                        </div>
                        <div class="col">
                            <div class="fw-bold">{{ $bookingCount ?? 0 }}</div>
                            <small>الحجوزات</small>
                        </div>
                        <div class="col">
                            <div class="fw-bold">{{ $transactionCount ?? 0 }}</div>
                            <small>المعاملات</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">روابط سريعة</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('customer.visas') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-passport me-2"></i> التأشيرات
                        </a>
                        <a href="{{ route('customer.bookings') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar me-2"></i> الحجوزات
                        </a>
                        <a href="{{ route('customer.transactions') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-history me-2"></i> سجل المعاملات
                        </a>
                        <a href="{{ route('customer.notifications') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-bell me-2"></i> التنبيهات
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="col-md-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">المعلومات الشخصية</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">الاسم الكامل</label>
                                <input type="text" class="form-control" name="name" value="{{ $customer->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" name="email" value="{{ $customer->email }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="tel" class="form-control" name="phone" value="{{ $customer->phone }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الجنسية</label>
                                <input type="text" class="form-control" name="nationality" value="{{ $customer->nationality }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">رقم جواز السفر</label>
                                <input type="text" class="form-control" name="passport_number" value="{{ $customer->passport_number }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">تغيير كلمة المرور</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">كلمة المرور الحالية</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-1"></i> تغيير كلمة المرور
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">إعدادات التنبيهات</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.notifications.preferences') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" {{ $customer->email_notifications ? 'checked' : '' }}>
                            <label class="form-check-label" for="emailNotifications">
                                تنبيهات البريد الإلكتروني
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="sms_notifications" id="smsNotifications" {{ $customer->sms_notifications ? 'checked' : '' }}>
                            <label class="form-check-label" for="smsNotifications">
                                تنبيهات الرسائل النصية
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-bell me-1"></i> حفظ إعدادات التنبيهات
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Photo Modal -->
<div class="modal fade" id="updatePhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تغيير الصورة الشخصية</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customer.profile.photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">اختر صورة جديدة</label>
                        <input type="file" class="form-control" name="photo" accept="image/*" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> رفع الصورة
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
