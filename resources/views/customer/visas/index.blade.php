@extends('layouts.customer')

@section('title', 'طلبات التأشيرات')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">طلبات التأشيرات</h4>
        <a href="{{ route('customer.visas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> تقديم طلب تأشيرة جديد
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نوع التأشيرة</th>
                        <th>تاريخ التقديم</th>
                        <th>الحالة</th>
                        <th>تاريخ الموافقة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visas as $visa)
                        <tr>
                            <td>{{ $visa->id }}</td>
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
                            <td>{{ $visa->approval_date ? $visa->approval_date->format('Y-m-d') : '-' }}</td>
                            <td>
                                <a href="{{ route('customer.visas.show', $visa) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> عرض التفاصيل
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
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
@endsection
