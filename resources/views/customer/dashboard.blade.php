@extends('layouts.customer')

@section('title', 'لوحة التحكم')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 mb-3">مرحباً بك في نظام إدارة السفر</h1>
                <p class="lead mb-4">نقدم لك خدمات متكاملة لإدارة رحلاتك وحجوزاتك بكل سهولة</p>
                <a href="{{ route('customer.bookings.create') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-plane me-2"></i> احجز رحلتك الآن
                </a>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://via.placeholder.com/500x300" alt="Hero Image" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<div class="container mt-5">
    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <i class="fas fa-passport fa-3x mb-3 text-primary"></i>
                    <h3>{{ $visaCount ?? 0 }}</h3>
                    <p class="text-muted mb-0">التأشيرات النشطة</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-3x mb-3 text-success"></i>
                    <h3>{{ $bookingCount ?? 0 }}</h3>
                    <p class="text-muted mb-0">الحجوزات الحالية</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <i class="fas fa-history fa-3x mb-3 text-info"></i>
                    <h3>{{ $transactionCount ?? 0 }}</h3>
                    <p class="text-muted mb-0">المعاملات المكتملة</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">آخر المعاملات</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>رقم المعاملة</th>
                        <th>نوع الخدمة</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions ?? [] as $transaction)
                    <tr>
                        <td>#{{ $transaction->id }}</td>
                        <td>{{ $transaction->service_type }}</td>
                        <td>
                            <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                                {{ $transaction->status }}
                            </span>
                        </td>
                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> عرض التفاصيل
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            لا توجد معاملات حديثة
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">إجراءات سريعة</h5>
                    <div class="list-group">
                        <a href="{{ route('customer.visas.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus-circle me-2"></i> تقديم طلب تأشيرة جديد
                        </a>
                        <a href="{{ route('customer.bookings.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-plus me-2"></i> إنشاء حجز جديد
                        </a>
                        <a href="{{ route('customer.profile.edit') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-edit me-2"></i> تحديث البيانات الشخصية
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">التنبيهات</h5>
                    <div class="list-group">
                        @forelse($notifications ?? [] as $notification)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $notification->message }}</p>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                <p class="mb-0">لا توجد تنبيهات جديدة</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
