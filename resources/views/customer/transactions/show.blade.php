@extends('layouts.customer')

@section('title', 'تفاصيل المعاملة')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Transaction Details -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">تفاصيل المعاملة #{{ $transaction->reference_id }}</h5>
                        <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ $transaction->status === 'completed' ? 'مكتمل' : ($transaction->status === 'pending' ? 'معلق' : 'فشل') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="text-muted mb-3">معلومات المعاملة</h6>
                            <p class="mb-2">
                                <strong>نوع الخدمة:</strong>
                                <span class="badge bg-{{ $transaction->service_type === 'visa' ? 'info' : 'primary' }}">
                                    {{ $transaction->service_type === 'visa' ? 'تأشيرة' : 'حجز' }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <strong>المبلغ:</strong>
                                {{ number_format($transaction->amount, 2) }} ريال
                            </p>
                            <p class="mb-2">
                                <strong>تاريخ المعاملة:</strong>
                                {{ $transaction->created_at->format('Y-m-d H:i') }}
                            </p>
                            <p class="mb-0">
                                <strong>رقم المرجع:</strong>
                                {{ $transaction->reference_id }}
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="text-muted mb-3">تفاصيل الخدمة</h6>
                            @if($transaction->service_type === 'visa')
                                <p class="mb-2">
                                    <strong>نوع التأشيرة:</strong>
                                    {{ $transaction->visa->type }}
                                </p>
                                <p class="mb-2">
                                    <strong>تاريخ التقديم:</strong>
                                    {{ $transaction->visa->submission_date }}
                                </p>
                                <p class="mb-0">
                                    <strong>حالة التأشيرة:</strong>
                                    {{ $transaction->visa->status }}
                                </p>
                            @else
                                <p class="mb-2">
                                    <strong>نوع الحجز:</strong>
                                    {{ $transaction->booking->service_type }}
                                </p>
                                <p class="mb-2">
                                    <strong>تاريخ الحجز:</strong>
                                    {{ $transaction->booking->booking_date }}
                                </p>
                                <p class="mb-0">
                                    <strong>الموقع:</strong>
                                    {{ $transaction->booking->location }}
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($transaction->description)
                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading">ملاحظات إضافية</h6>
                            <p class="mb-0">{{ $transaction->description }}</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('customer.transactions') }}" class="btn btn-light">
                            <i class="fas fa-arrow-right me-1"></i> العودة للمعاملات
                        </a>
                        @if($transaction->status === 'completed')
                            <a href="{{ route('customer.transactions.receipt', $transaction) }}" 
                               class="btn btn-success" target="_blank">
                                <i class="fas fa-file-invoice me-1"></i> تحميل الفاتورة
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">سجل المعاملة</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">إنشاء المعاملة</h6>
                                <p class="text-muted mb-0">{{ $transaction->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        @if($transaction->status === 'completed')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">اكتمال المعاملة</h6>
                                    <p class="text-muted mb-0">{{ $transaction->updated_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                        @elseif($transaction->status === 'failed')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">فشل المعاملة</h6>
                                    <p class="text-muted mb-0">{{ $transaction->updated_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 25px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
}

.timeline-item:not(:last-child) .timeline-marker::after {
    content: '';
    position: absolute;
    left: 50%;
    top: 15px;
    bottom: -25px;
    width: 2px;
    margin-left: -1px;
    background-color: #e9ecef;
}

.timeline-content {
    padding-bottom: 10px;
}
</style>
@endsection
