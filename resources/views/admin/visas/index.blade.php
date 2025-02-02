@extends('layouts.admin')

@section('title', 'إدارة التأشيرات')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">إدارة التأشيرات</h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-primary">إجمالي الطلبات: {{ $visas->total() }}</span>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom">
            <form action="{{ route('admin.visas.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">بحث</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="اسم العميل أو البريد الإلكتروني..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">نوع التأشيرة</label>
                    <select name="type" class="form-select">
                        <option value="">الكل</option>
                        <option value="hajj" {{ request('type') === 'hajj' ? 'selected' : '' }}>تأشيرة حج</option>
                        <option value="umrah" {{ request('type') === 'umrah' ? 'selected' : '' }}>تأشيرة عمرة</option>
                        <option value="work" {{ request('type') === 'work' ? 'selected' : '' }}>تأشيرة عمل</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتملة</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> بحث
                        </button>
                        <a href="{{ route('admin.visas.index') }}" class="btn btn-light">
                            <i class="fas fa-redo me-1"></i> إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Visas Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>نوع التأشيرة</th>
                        <th>تاريخ التقديم</th>
                        <th>الحالة</th>
                        <th>المستندات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visas as $visa)
                        <tr>
                            <td>{{ $visa->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/40" 
                                         class="rounded-circle me-2" alt="{{ $visa->customer->name }}">
                                    <div>
                                        <h6 class="mb-0">{{ $visa->customer->name }}</h6>
                                        <small class="text-muted">{{ $visa->customer->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($visa->type === 'hajj')
                                    <span class="badge bg-info">تأشيرة حج</span>
                                @elseif($visa->type === 'umrah')
                                    <span class="badge bg-primary">تأشيرة عمرة</span>
                                @else
                                    <span class="badge bg-secondary">تأشيرة عمل</span>
                                @endif
                            </td>
                            <td>{{ $visa->submission_date->format('Y-m-d') }}</td>
                            <td>
                                @if($visa->status === 'pending')
                                    <span class="badge bg-warning">قيد الانتظار</span>
                                @elseif($visa->status === 'completed')
                                    <span class="badge bg-success">مكتملة</span>
                                @else
                                    <span class="badge bg-danger">مرفوضة</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $visa->documents->count() }} مستندات
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.visas.show', $visa) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                @if($visa->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-success"
                                            onclick="updateStatus('{{ $visa->id }}', 'completed')">
                                        <i class="fas fa-check"></i> قبول
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="updateStatus('{{ $visa->id }}', 'rejected')">
                                        <i class="fas fa-times"></i> رفض
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <img src="https://via.placeholder.com/80" alt="No Visas" class="mb-3">
                                <p class="text-muted mb-0">لا توجد طلبات تأشيرات</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visas->hasPages())
            <div class="card-footer bg-white">
                {{ $visas->links() }}
            </div>
        @endif
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
</script>
@endpush
@endsection
