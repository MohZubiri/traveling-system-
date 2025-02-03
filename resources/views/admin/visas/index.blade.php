@extends('layouts.admin')

@section('title', __('messages.visa_management'))

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">{{ __('messages.visa_management') }}</h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-primary">{{ __('messages.total_requests') }}: {{ $visas->total() }}</span>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-body border-bottom">
            <form action="{{ route('admin.visas.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ __('messages.search') }}</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="{{ __('messages.search_placeholder') }}"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('messages.visa_type') }}</label>
                    <select name="type" class="form-select">
                        <option value="">{{ __('messages.all') }}</option>
                        <option value="hajj" {{ request('type') === 'hajj' ? 'selected' : '' }}>{{ __('messages.hajj_visa') }}</option>
                        <option value="umrah" {{ request('type') === 'umrah' ? 'selected' : '' }}>{{ __('messages.umrah_visa') }}</option>
                        <option value="work" {{ request('type') === 'work' ? 'selected' : '' }}>{{ __('messages.work_visa') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('messages.status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('messages.all') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> {{ __('messages.search') }}
                        </button>
                        <a href="{{ route('admin.visas.index') }}" class="btn btn-light">
                            <i class="fas fa-redo me-1"></i> {{ __('messages.reset') }}
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
                        <th>{{ __('messages.customer') }}</th>
                        <th>{{ __('messages.visa_type') }}</th>
                        <th>{{ __('messages.submission_date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.documents') }}</th>
                        <th>{{ __('messages.actions') }}</th>
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
                                    <span class="badge bg-info">{{ __('messages.hajj_visa') }}</span>
                                @elseif($visa->type === 'umrah')
                                    <span class="badge bg-primary">{{ __('messages.umrah_visa') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('messages.work_visa') }}</span>
                                @endif
                            </td>
                            <td>{{ $visa->submission_date->format('Y-m-d') }}</td>
                            <td>
                                @if($visa->status === 'pending')
                                    <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                                @elseif($visa->status === 'completed')
                                    <span class="badge bg-success">{{ __('messages.completed') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('messages.rejected') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ __('messages.documents_count', ['count' => $visa->documents->count()]) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.visas.show', $visa) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> {{ __('messages.view') }}
                                </a>
                                @if($visa->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-success"
                                            onclick="updateStatus('{{ $visa->id }}', 'completed')">
                                        <i class="fas fa-check"></i> {{ __('messages.accept') }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="updateStatus('{{ $visa->id }}', 'rejected')">
                                        <i class="fas fa-times"></i> {{ __('messages.reject') }}
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <img src="https://via.placeholder.com/80" alt="No Visas" class="mb-3">
                                <p class="text-muted mb-0">{{ __('messages.no_visas') }}</p>
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
                    <h5 class="modal-title">{{ __('messages.update_visa_status') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.notes') }}</label>
                        <textarea name="notes" class="form-control" rows="3"
                                placeholder="{{ __('messages.notes_placeholder') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
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
