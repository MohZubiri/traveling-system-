@extends('layouts.admin')

@section('title', 'تفاصيل طلب التأشيرة')

@section('content')
<div class="container-fluid py-4">
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
                    <!-- Customer Info -->
                    <div class="d-flex align-items-center mb-4">
                        <img src="https://via.placeholder.com/64" class="rounded-circle me-3" alt="{{ $visa->customer->name }}">
                        <div>
                            <h6 class="mb-1">{{ $visa->customer->name }}</h6>
                            <p class="mb-1">
                                <i class="fas fa-envelope text-muted me-1"></i> {{ $visa->customer->email }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-phone text-muted me-1"></i> {{ $visa->customer->phone }}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">معلومات التأشيرة</h6>
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
                                <h6 class="mb-3">معلومات المعاملة</h6>
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
                @if($visa->status === 'pending')
                    <div class="card-footer bg-white">
                        <button type="button" class="btn btn-success"
                                onclick="updateStatus('{{ $visa->id }}', 'completed')">
                            <i class="fas fa-check me-1"></i> قبول الطلب
                        </button>
                        <button type="button" class="btn btn-danger"
                                onclick="updateStatus('{{ $visa->id }}', 'rejected')">
                            <i class="fas fa-times me-1"></i> رفض الطلب
                        </button>
                    </div>
                @endif
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
                                <th>تاريخ الرفع</th>
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
                                    <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.visas.documents.download', $document) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> تحميل
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info"
                                                onclick="previewDocument('{{ route('admin.visas.documents.preview', $document) }}')">
                                            <i class="fas fa-eye"></i> معاينة
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
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
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">سجل الحالة</h5>
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
                                    @if($visa->notes)
                                        <p class="text-muted mt-2 mb-0">
                                            <small>{{ $visa->notes }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">إجراءات سريعة</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $visa->customer->email }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-1"></i> مراسلة العميل
                        </a>
                        <button type="button" class="btn btn-outline-info" onclick="printDetails()">
                            <i class="fas fa-print me-1"></i> طباعة التفاصيل
                        </button>
                        <a href="{{ route('admin.visas.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right me-1"></i> العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">تحديث حالة التأشيرة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                placeholder="أدخل ملاحظات حول قرارك..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">معاينة المستند</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <iframe id="previewFrame" src="" style="width: 100%; height: 500px; border: none;"></iframe>
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

@push('scripts')
<script>
function updateStatus(visaId, status) {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    const form = document.getElementById('statusForm');
    form.action = `/admin/visas/${visaId}/status`;
    form.querySelector('input[name="_method"]').value = 'PUT';
    
    // Add hidden status input
    let statusInput = form.querySelector('input[name="status"]');
    if (!statusInput) {
        statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        form.appendChild(statusInput);
    }
    statusInput.value = status;
    
    modal.show();
}

function previewDocument(url) {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    document.getElementById('previewFrame').src = url;
    modal.show();
}

function printDetails() {
    window.print();
}
</script>
@endpush
@endsection
