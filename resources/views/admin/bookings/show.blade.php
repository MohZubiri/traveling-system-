@extends('layouts.admin')

@section('title', 'تفاصيل الحجز')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Booking Details -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">تفاصيل الحجز #{{ $booking->id }}</h5>
                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ $booking->status === 'confirmed' ? 'مؤكد' : ($booking->status === 'pending' ? 'معلق' : 'ملغي') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted d-block mb-2">نوع الخدمة</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-{{ $booking->service_type === 'bus' ? 'bus' : 'car' }} fa-2x text-primary me-2"></i>
                                <span>{{ $booking->service_type === 'bus' ? 'حافلة' : 'سيارة' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted d-block mb-2">عدد التذاكر</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ticket-alt fa-2x text-primary me-2"></i>
                                <span>{{ $booking->tickets->count() }} تذاكر</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted d-block mb-2">التاريخ</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt fa-2x text-primary me-2"></i>
                                <span>{{ $booking->date->format('Y-m-d') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted d-block mb-2">الوقت</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock fa-2x text-primary me-2"></i>
                                <span>{{ date('H:i', strtotime($booking->time)) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted d-block mb-2">موقع الانطلاق</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt fa-2x text-primary me-2"></i>
                            <span>{{ $booking->location }}</span>
                        </div>
                    </div>

                    <div class="alert alert-primary mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>التكلفة الإجمالية</span>
                            <strong>{{ number_format($booking->cost, 2) }} ريال</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">التذاكر</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>رقم التذكرة</th>
                                    <th>تاريخ الإصدار</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->ticket_number }}</td>
                                        <td>{{ $ticket->issue_date->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $ticket->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ $ticket->status === 'active' ? 'فعال' : 'مستخدم' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Customer Info -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">معلومات العميل</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="https://via.placeholder.com/64" alt="Customer Avatar" class="rounded-circle me-3">
                        <div>
                            <h6 class="mb-1">{{ $booking->customer->name }}</h6>
                            <div class="text-muted">{{ $booking->customer->email }}</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block mb-2">رقم الهاتف</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <span>{{ $booking->customer->phone }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">تحديث الحالة</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-select" required>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $booking->notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
