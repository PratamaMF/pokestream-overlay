@extends('layout.main')

@section('title', 'Activity Log - '  . Auth::user()->name)
@section('namepage', 'System Activity Log')
@section('route', route('activity.index'))
@section('namemenu', 'Activity Log')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark"><i class="fas fa-history me-2 text-primary"></i>System Activity Logs</h6>
        
        <button type="button" class="btn btn-sm btn-soft-danger px-3 fw-bold" 
                data-bs-toggle="modal" data-bs-target="#clearHistoryModal"
                @if($logs->isEmpty()) disabled title="Tidak ada data log yang bisa dihapus" @endif>
            <i class="fas fa-trash-alt me-1"></i> Clear History
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="datatablesSimple" class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th class="px-4" style="width: 80px;">No</th>
                        <th style="width: 200px;">Timestamp</th>
                        <th style="width: 150px;">Module</th>
                        <th>Activity</th>
                        <th class="text-center" style="width: 120px;">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td class="px-4 fw-bold text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="text-dark fw-medium d-block">
                                <i class="far fa-clock me-1 text-muted small"></i>
                                {{ $log->created_at->format('d M Y, H:i:s') }} WIB
                            </span>
                            <small class="text-muted text-uppercase" style="font-size: 9px;">{{ $log->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($log->module === 'Product')
                                <span class="badge bg-soft-primary text-primary px-2 py-1.5 fw-medium"><i class="fas fa-box me-1"></i>Product</span>
                            @elseif($log->module === 'Category')
                                <span class="badge bg-soft-warning text-warning px-2 py-1.5 fw-medium"><i class="fas fa-tags me-1"></i>Category</span>
                            @elseif($log->module === 'Note')
                                <span class="badge bg-soft-info text-info px-2 py-1.5 fw-medium"><i class="fas fa-sticky-note me-1"></i>Note</span>
                            @else
                                <span class="badge bg-soft-secondary text-secondary px-2 py-1.5 fw-medium"><i class="fas fa-cog me-1"></i>System</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold text-dark text-wrap d-block" style="max-width: 500px;">{{ $log->activity }}</span>
                        </td>
                        <td class="text-center px-4">
                            @if($log->details)
                                <button type="button" class="btn btn-sm btn-soft-primary px-3" data-bs-toggle="modal" data-bs-target="#detailLogModal{{ $log->id }}">
                                    <i class="fas fa-search-plus me-1"></i> View
                                </button>
                            @else
                                <span class="text-muted small italic">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Not found any activity log.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="clearHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0 py-3">
                <h5 class="modal-title fw-bold" style="font-size: 16px;"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Clear System Audit Logs</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('activity.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4">
                    <p class="text-muted small">You will permanently delete traces of system activity. This action cannot be undone.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Select Deletion Range</label>
                        <select name="range" class="form-select" required>
                            <option value="24_hours">Delete Logs Older Than 24 Hours</option>
                            <option value="1_week">Delete Logs Older Than 1 Week</option>
                            <option value="1_month">Delete Logs Older Than 1 Month</option>
                            <option value="3_months">Delete Logs Older Than 3 Months</option>
                            <option value="1_year">Delete Logs Older Than 1 Year</option>
                            <option value="all">— Delete All Logs —</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-dark">ConfirmPassword</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Input your password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light py-2.5">
                    <button type="button" class="btn btn-soft-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold shadow-sm">
                        <i class="fas fa-trash-alt me-1"></i> Execute Permanently Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($logs as $log)
    @if($log->details)
    <div class="modal fade" id="detailLogModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-info-circle me-2 text-primary"></i>Log Data Audit Trail Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <small class="text-muted text-uppercase d-block fw-bold mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Activity Details</small>
                        <h6 class="fw-bold text-dark mb-0">{{ $log->activity }}</h6>
                    </div>

                    <hr class="opacity-5 my-3" />

                    @if(($log->details['action'] ?? '') === 'update')
                        <small class="text-muted text-uppercase d-block fw-bold mb-3" style="font-size: 11px; letter-spacing: 0.5px;">Details of Data Field Changes</small>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 150px;">Field Name</th>
                                        <th class="text-danger">Old Value</th>
                                        <th class="text-success">New Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($log->details['changes'] as $field => $value)
                                    <tr>
                                        <td class="fw-bold text-dark bg-light">{{ $field }}</td>
                                        <td class="text-wrap text-danger" style="max-width: 250px;">
                                            {!! !empty($value['old']) ? nl2br(e($value['old'])) : '<em class="text-muted small">empty</em>' !!}
                                        </td>
                                        <td class="text-wrap text-success" style="max-width: 250px;">
                                            {!! !empty($value['new']) ? nl2br(e($value['new'])) : '<em class="text-muted small">empty</em>' !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <small class="text-muted text-uppercase d-block fw-bold mb-3" style="font-size: 11px; letter-spacing: 0.5px;">Information Data</small>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 150px;">Field Name</th>
                                        <th>Inputted / Deleted Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($log->details['data'] as $field => $value)
                                    <tr>
                                        <td class="fw-bold text-dark bg-light">{{ $field }}</td>
                                        <td class="text-wrap text-dark text-break" style="max-width: 450px;">
                                            {!! !empty($value) ? nl2br(e($value)) : '<em class="text-muted small">empty</em>' !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light border-0 py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection