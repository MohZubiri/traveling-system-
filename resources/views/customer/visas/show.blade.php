@extends('layouts.customer')

@section('title', 'تفاصيل طلب التأشيرة')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Visa Details -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">تفاصيل طلب التأشيرة #{{ $visa->id }}</h5>
                        <span class="badge bg-{{ $visa->status === 'pending' ? 'warning' : ($visa->status === 'completed' ? 'success' : 'danger') }}">
                            {{ $visa->status === 'pending' ? 'قيد الانتظار' : ($visa->status === 'completed' ? 'مكتملة' : 'مرفوضة') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>نوع التأشيرة:</strong>
                                @if($visa->type === 'hajj')
                                    <span class="badge bg-info">تأشيرة حج</span>
                                @elseif($visa->type === 'umrah')
                                    <span class="badge bg-primary">تأشيرة عمرة</span>
                                @else
                                    <span class="badge bg-secondary">تأشيرة عمل</span>
                                @endif
                            </p>
                            <p class="mb-2">
                                <strong>تاريخ التقديم:</strong>
                                {{ $visa->submission_date->format('Y-m-d') }}
                            </p>
                            @if($visa->approval_date)
                                <p class="mb-2">
                                    <strong>تاريخ {{ $visa->status === 'completed' ? 'الموافقة' : 'الرفض' }}:</strong>
                                    {{ $visa->approval_date->format('Y-m-d') }}
                                </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($visa->transaction)
                                <p class="mb-2">
                                    <strong>رقم المعاملة:</strong>
                                    {{ $visa->transaction->reference_id }}
                                </p>
                                <p class="mb-2">
                                    <strong>المبلغ:</strong>
                                    {{ number_format($visa->transaction->amount, 2) }} ريال
                                </p>
                                <p class="mb-2">
                                    <strong>حالة الدفع:</strong>
                                    <span class="badge bg-{{ $visa->transaction->status === 'completed' ? 'success' : ($visa->transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ $visa->transaction->status === 'completed' ? 'مدفوع' : ($visa->transaction->status === 'pending' ? 'معلق' : 'فشل') }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($visa->notes)
                        <div class="alert alert-info mt-3 mb-0">
                            <h6 class="alert-heading">ملاحظات</h6>
                            <p class="mb-0">{{ $visa->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">المستندات المرفقة</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>اسم المستند</th>
                                <th>نوع الملف</th>
                                <th>الحجم</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($visa->documents as $document)
                                <tr>
                                    <td>
                                        @if($document->document_name === 'passport')
                                            صورة جواز السفر
                                        @elseif($document->document_name === 'photo')
                                            الصورة الشخصية
                                        @elseif($document->document_name === 'cv')
                                            السيرة الذاتية
                                        @elseif($document->document_name === 'experience')
                                            شهادات الخبرة
                                        @elseif($document->document_name === 'vaccination')
                                            شهادة التطعيم
                                        @else
                                            {{ $document->document_name }}
                                        @endif
                                    </td>
                                    <td>{{ strtoupper(pathinfo($document->original_name, PATHINFO_EXTENSION)) }}</td>
                                    <td>{{ number_format($document->size / 1024, 2) }} KB</td>
                                    <td>
                                        <a href="{{ route('customer.visas.documents.download', $document) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> تحميل
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="text-muted mb-0">لا توجد مستندات مرفقة</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Timeline -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">حالة الطلب</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">تم تقديم الطلب</h6>
                                <small class="text-muted">{{ $visa->submission_date->format('Y-m-d H:i') }}</small>
                            </div>
                        </div>

                        @if($visa->status !== 'pending')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $visa->status === 'completed' ? 'success' : 'danger' }}"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">
                                        {{ $visa->status === 'completed' ? 'تمت الموافقة على الطلب' : 'تم رفض الطلب' }}
                                    </h6>
                                    <small class="text-muted">{{ $visa->approval_date->format('Y-m-d H:i') }}</small>
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
