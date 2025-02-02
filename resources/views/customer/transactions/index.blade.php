@extends('layouts.customer')

@section('title', 'المعاملات المالية')

@section('content')
<div class="container py-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">إجمالي المدفوعات المكتملة</h6>
                            <h3 class="card-title mb-0">{{ number_format($totalAmount, 2) }} ريال</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">المدفوعات المعلقة</h6>
                            <h3 class="card-title mb-0">{{ number_format($pendingAmount, 2) }} ريال</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('customer.transactions') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">نوع الخدمة</label>
                    <select name="type" class="form-select">
                        <option value="">الكل</option>
                        <option value="visa" {{ request('type') === 'visa' ? 'selected' : '' }}>تأشيرة</option>
                        <option value="booking" {{ request('type') === 'booking' ? 'selected' : '' }}>حجز</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>فشل</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> تصفية
                    </button>
                    <a href="{{ route('customer.transactions') }}" class="btn btn-light">
                        <i class="fas fa-redo me-1"></i> إعادة تعيين
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">سجل المعاملات</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نوع الخدمة</th>
                        <th>المبلغ</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->reference_id }}</td>
                            <td>
                                <span class="badge bg-{{ $transaction->service_type === 'visa' ? 'info' : 'primary' }}">
                                    {{ $transaction->service_type === 'visa' ? 'تأشيرة' : 'حجز' }}
                                </span>
                            </td>
                            <td>{{ number_format($transaction->amount, 2) }} ريال</td>
                            <td>
                                @if($transaction->status === 'completed')
                                    <span class="badge bg-success">مكتمل</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge bg-warning">معلق</span>
                                @else
                                    <span class="badge bg-danger">فشل</span>
                                @endif
                            </td>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('customer.transactions.show', $transaction) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                @if($transaction->status === 'completed')
                                    <a href="{{ route('customer.transactions.receipt', $transaction) }}" 
                                       class="btn btn-sm btn-success" target="_blank">
                                        <i class="fas fa-file-invoice"></i> الفاتورة
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <img src="https://via.placeholder.com/80" alt="No Transactions" class="mb-3">
                                <p class="text-muted mb-0">لا توجد معاملات</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer bg-white">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
