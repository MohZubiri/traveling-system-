@extends('layouts.admin')

@section('title', 'إدارة الحجوزات')

@section('content')
<div class="container-fluid py-4">
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.bookings.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">نوع الخدمة</label>
                        <select name="service_type" class="form-select">
                            <option value="">الكل</option>
                            <option value="bus" {{ request('service_type') === 'bus' ? 'selected' : '' }}>حافلة</option>
                            <option value="car" {{ request('service_type') === 'car' ? 'selected' : '' }}>سيارة</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">الكل</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">بحث</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="اسم العميل أو البريد الإلكتروني..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> بحث
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-light">
                            <i class="fas fa-redo me-1"></i> إعادة تعيين
                        </a>
                        <a href="{{ route('admin.bookings.report') }}" class="btn btn-success float-end">
                            <i class="fas fa-chart-bar me-1"></i> تقرير الحجوزات
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">الحجوزات</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم الحجز</th>
                            <th>العميل</th>
                            <th>نوع الخدمة</th>
                            <th>التاريخ</th>
                            <th>الوقت</th>
                            <th>عدد التذاكر</th>
                            <th>التكلفة</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>
                                    <div>{{ $booking->customer->name }}</div>
                                    <small class="text-muted">{{ $booking->customer->email }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-{{ $booking->service_type === 'bus' ? 'bus' : 'car' }} text-primary me-1"></i>
                                    {{ $booking->service_type === 'bus' ? 'حافلة' : 'سيارة' }}
                                </td>
                                <td>{{ $booking->date->format('Y-m-d') }}</td>
                                <td>{{ date('H:i', strtotime($booking->time)) }}</td>
                                <td>{{ $booking->tickets->count() }}</td>
                                <td>{{ number_format($booking->cost, 2) }} ريال</td>
                                <td>
                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ $booking->status === 'confirmed' ? 'مؤكد' : ($booking->status === 'pending' ? 'معلق' : 'ملغي') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <img src="https://via.placeholder.com/80" alt="No Bookings" class="mb-3">
                                    <h6>لا توجد حجوزات</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
