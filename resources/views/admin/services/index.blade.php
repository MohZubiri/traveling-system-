@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">إدارة الخدمات</h2>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة خدمة جديدة
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>اسم الخدمة</th>
                            <th>السعر</th>
                            <th>مميزة</th>
                            <th>نشطة</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td>
                                @if($service->image)
                                <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="img-thumbnail" style="max-width: 100px;">
                                @else
                                <span class="text-muted">لا توجد صورة</span>
                                @endif
                            </td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->price ? number_format($service->price, 2) . ' ريال' : 'غير محدد' }}</td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input toggle-featured" 
                                           id="featured{{ $service->id }}" 
                                           {{ $service->is_featured ? 'checked' : '' }}
                                           data-id="{{ $service->id }}">
                                    <label class="custom-control-label" for="featured{{ $service->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input toggle-active" 
                                           id="active{{ $service->id }}" 
                                           {{ $service->active ? 'checked' : '' }}
                                           data-id="{{ $service->id }}">
                                    <label class="custom-control-label" for="active{{ $service->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> تعديل
                                </a>
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الخدمة؟')">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">لا توجد خدمات</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $services->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-featured').change(function() {
        const serviceId = $(this).data('id');
        $.post(`/admin/services/${serviceId}/toggle-featured`, {
            _token: '{{ csrf_token() }}'
        })
        .fail(function() {
            alert('حدث خطأ أثناء تحديث حالة الخدمة');
            $(this).prop('checked', !$(this).prop('checked'));
        });
    });

    $('.toggle-active').change(function() {
        const serviceId = $(this).data('id');
        $.post(`/admin/services/${serviceId}/toggle-active`, {
            _token: '{{ csrf_token() }}'
        })
        .fail(function() {
            alert('حدث خطأ أثناء تحديث حالة الخدمة');
            $(this).prop('checked', !$(this).prop('checked'));
        });
    });
});
</script>
@endpush
@endsection
