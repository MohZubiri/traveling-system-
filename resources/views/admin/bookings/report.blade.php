@extends('layouts.admin')

@section('title', 'تقرير الحجوزات')

@section('content')
<div class="container-fluid py-4">
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.bookings.report') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">نوع الخدمة</label>
                        <select name="service_type" class="form-select">
                            <option value="">الكل</option>
                            <option value="bus" {{ request('service_type') === 'bus' ? 'selected' : '' }}>حافلة</option>
                            <option value="car" {{ request('service_type') === 'car' ? 'selected' : '' }}>سيارة</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> عرض التقرير
                        </button>
                        <a href="{{ route('admin.bookings.report') }}" class="btn btn-light">
                            <i class="fas fa-redo me-1"></i> إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">إجمالي الحجوزات</h6>
                            <h3 class="mb-0">{{ $totalBookings }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-calendar-check fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">الحجوزات المؤكدة</h6>
                            <h3 class="mb-0">{{ $confirmedBookings }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">الحجوزات الملغاة</h6>
                            <h3 class="mb-0">{{ $cancelledBookings }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">إجمالي الإيرادات</h6>
                            <h3 class="mb-0">{{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-money-bill-wave fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">توزيع الحجوزات</h5>
        </div>
        <div class="card-body">
            <canvas id="bookingsChart" height="100"></canvas>
        </div>
    </div>

    <!-- Detailed Report -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">تفاصيل الحجوزات</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>نوع الخدمة</th>
                            <th>عدد الحجوزات</th>
                            <th>الإيرادات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings->groupBy('date') as $date => $dateBookings)
                            <tr>
                                <td>{{ $date }}</td>
                                <td>
                                    حافلات: {{ $dateBookings->where('service_type', 'bus')->count() }}<br>
                                    سيارات: {{ $dateBookings->where('service_type', 'car')->count() }}
                                </td>
                                <td>{{ $dateBookings->count() }}</td>
                                <td>{{ number_format($dateBookings->where('status', 'confirmed')->sum('cost'), 2) }} ريال</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('bookingsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bookings->pluck('date')->unique()) !!},
            datasets: [{
                label: 'حافلات',
                data: {!! json_encode($bookings->groupBy('date')->map->where('service_type', 'bus')->map->count()) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }, {
                label: 'سيارات',
                data: {!! json_encode($bookings->groupBy('date')->map->where('service_type', 'car')->map->count()) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgb(255, 99, 132)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection
