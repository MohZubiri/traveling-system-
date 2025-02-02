@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Customers List</h3>
        <div class="card-actions">
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                <i class="ti ti-plus"></i> Add New Customer
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Nationality</th>
                        <th>Created At</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->nationality }}</td>
                            <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-primary">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
