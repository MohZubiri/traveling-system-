@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row row-deck row-cards">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Customers</div>
                </div>
                <div class="h1 mb-3">{{ \App\Models\Customer::count() }}</div>
                <div class="d-flex mb-2">
                    <div>Active customers in the system</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Pending Visas</div>
                </div>
                <div class="h1 mb-3">{{ \App\Models\Visa::where('status', 'pending')->count() }}</div>
                <div class="d-flex mb-2">
                    <div>Visa applications awaiting processing</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Active Bookings</div>
                </div>
                <div class="h1 mb-3">{{ \App\Models\Booking::where('status', 'confirmed')->count() }}</div>
                <div class="d-flex mb-2">
                    <div>Current active travel bookings</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Revenue</div>
                </div>
                <div class="h1 mb-3">{{ number_format(\App\Models\Payment::where('status', 'completed')->sum('amount'), 2) }}</div>
                <div class="d-flex mb-2">
                    <div>Total completed payments</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Bookings</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Booking::with('customer')->latest()->take(5)->get() as $booking)
                        <tr>
                            <td>{{ $booking->customer->name }}</td>
                            <td>{{ $booking->service_type }}</td>
                            <td>{{ $booking->booking_date->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($booking->cost, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
